<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments_model extends CI_Model
{
    /**
     * Get all payments for logged-in user
     */
    public function get_all_payments($user_id)
    {
        $this->db->select('payments.*, invoices.invoice_number, customers.name as customer_name');
        $this->db->join('invoices', 'invoices.id = payments.invoice_id');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('payments.user_id', $user_id);
        $this->db->order_by('payments.created_at', 'DESC');
        $query = $this->db->get('payments');
        return $query->result();
    }

    /**
     * Get single payment (with user_id check)
     */
    public function get_payment($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('payments');
        return $query->row();
    }

    /**
     * Get payments by invoice
     */
    public function get_payments_by_invoice($invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $this->db->order_by('payment_date', 'DESC');
        $query = $this->db->get('payments');
        return $query->result();
    }

    /**
     * Create payment for logged-in user
     */
    public function create_payment($data, $user_id)
    {
        $this->db->trans_start();

        $data['user_id'] = $user_id;
        $this->db->insert('payments', $data);

        // Update invoice status
        $this->update_invoice_status($data['invoice_id']);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Delete payment (only if belongs to user)
     */
    public function delete_payment($id, $user_id)
    {
        $payment = $this->get_payment($id, $user_id);

        if (!$payment) {
            return false;
        }

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->delete('payments');

        // Update invoice status
        $this->update_invoice_status($payment->invoice_id);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Update invoice status based on payments
     */
    private function update_invoice_status($invoice_id)
    {
        // Get invoice total
        $this->db->select('grand_total');
        $this->db->where('id', $invoice_id);
        $invoice = $this->db->get('invoices')->row();

        // Get total paid
        $this->db->select('SUM(amount) as total_paid');
        $this->db->where('invoice_id', $invoice_id);
        $result = $this->db->get('payments')->row();
        $total_paid = $result->total_paid ?: 0;

        // Determine status
        $status = 'Unpaid';
        if ($total_paid >= $invoice->grand_total) {
            $status = 'Paid';
        } elseif ($total_paid > 0) {
            $status = 'Partial';
        }

        // Update invoice
        $this->db->where('id', $invoice_id);
        $this->db->update('invoices', ['status' => $status]);
    }
}
