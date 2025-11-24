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
}
