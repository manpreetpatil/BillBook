<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices_model extends CI_Model
{
    /**
     * Get all invoices for logged-in user
     */
    public function get_all_invoices($user_id)
    {
        $this->db->select('invoices.*, customers.name as customer_name');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->order_by('invoices.created_at', 'DESC');
        $query = $this->db->get('invoices');
        return $query->result();
    }

    /**
     * Get single invoice (with user_id check)
     */
    public function get_invoice($id, $user_id)
    {
        $this->db->select('invoices.*, customers.name as customer_name, customers.email, customers.phone, customers.address, customers.gstin');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.id', $id);
        $this->db->where('invoices.user_id', $user_id);
        $query = $this->db->get('invoices');
        return $query->row();
    }

    /**
     * Get invoice items
     */
    public function get_invoice_items($invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $query = $this->db->get('invoice_items');
        return $query->result();
    }

    /**
     * Create invoice for logged-in user
     */
    public function create_invoice($invoice_data, $items_data, $user_id)
    {
        $this->db->trans_start();

        $invoice_data['user_id'] = $user_id;
        $this->db->insert('invoices', $invoice_data);
        $invoice_id = $this->db->insert_id();

        foreach ($items_data as $item) {
            $item['invoice_id'] = $invoice_id;
            $this->db->insert('invoice_items', $item);

            // Deduct stock (FIFO)
            $this->_deduct_stock($item['item_id'], $item['quantity'], $user_id, $invoice_id);
        }

        $this->db->trans_complete();

        return $this->db->trans_status() ? $invoice_id : false;
    }

    /**
     * Update invoice (only if belongs to user)
     */
    public function update_invoice($id, $invoice_data, $items_data, $user_id)
    {
        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->update('invoices', $invoice_data);

        // Delete old items
        $this->db->where('invoice_id', $id);
        $this->db->delete('invoice_items');

        // Insert new items
        foreach ($items_data as $item) {
            $item['invoice_id'] = $id;
            $this->db->insert('invoice_items', $item);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Delete invoice (only if belongs to user)
     */
    public function delete_invoice($id, $user_id)
    {
        $this->db->trans_start();

        $this->db->where('invoice_id', $id);
        $this->db->delete('invoice_items');

        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->delete('invoices');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Generate invoice number for user
     */
    public function generate_invoice_number($user_id)
    {
        $this->load->model('Settings_model');
        $settings = $this->Settings_model->get_settings($user_id);
        $prefix = $settings ? $settings->invoice_prefix : 'INV-';

        // Get the last invoice number for this user
        $this->db->select('invoice_number');
        $this->db->where('user_id', $user_id);
        $this->db->like('invoice_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('invoices');

        if ($query->num_rows() > 0) {
            $last_invoice = $query->row();
            $last_number = (int) str_replace($prefix, '', $last_invoice->invoice_number);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        return $prefix . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Update invoice status (only if belongs to user)
     */
    public function update_invoice_status($invoice_id, $status, $user_id)
    {
        $this->db->where('id', $invoice_id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('invoices', ['status' => $status]);
    }

    /**
     * Deduct stock using FIFO logic
     */
    private function _deduct_stock($item_id, $quantity, $user_id, $invoice_id)
    {
        // 1. Get batches ordered by expiry (ASC) and creation (ASC)
        $this->db->where('item_id', $item_id);
        $this->db->where('quantity >', 0);
        $this->db->order_by('expiry_date', 'ASC'); // First expiring first
        $this->db->order_by('created_at', 'ASC');  // Oldest stock first
        $batches = $this->db->get('item_batches')->result();

        $qty_to_deduct = $quantity;

        foreach ($batches as $batch) {
            if ($qty_to_deduct <= 0)
                break;

            $deducted = 0;
            if ($batch->quantity >= $qty_to_deduct) {
                // This batch has enough stock
                $deducted = $qty_to_deduct;
                $new_batch_qty = $batch->quantity - $qty_to_deduct;
                $qty_to_deduct = 0;
            } else {
                // Take all from this batch
                $deducted = $batch->quantity;
                $new_batch_qty = 0;
                $qty_to_deduct -= $batch->quantity;
            }

            // Update batch quantity
            $this->db->where('id', $batch->id);
            $this->db->update('item_batches', ['quantity' => $new_batch_qty]);
        }

        // 2. Update total item stock
        $this->db->set('current_stock', 'current_stock - ' . (float) $quantity, FALSE);
        $this->db->where('id', $item_id);
        $this->db->update('items');

        // 3. Log movement
        $log_data = [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'type' => 'OUT',
            'quantity' => $quantity,
            'reference_type' => 'Invoice',
            'reference_id' => $invoice_id,
            'date' => date('Y-m-d'),
            'notes' => 'Invoice Creation'
        ];
        $this->db->insert('inventory_logs', $log_data);
    }
    public function get_invoice_by_hash($hash)
    {
        $this->db->select('invoices.*, customers.name as customer_name, customers.email as customer_email, customers.phone as customer_phone, customers.address as customer_address, users.name as user_name, users.email as user_email');
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->join('users', 'users.id = invoices.user_id');
        $this->db->where('invoices.share_hash', $hash);
        $query = $this->db->get();
        return $query->row();
    }

    public function update_share_hash($id, $hash)
    {
        $this->db->where('id', $id);
        return $this->db->update('invoices', ['share_hash' => $hash]);
    }
}
