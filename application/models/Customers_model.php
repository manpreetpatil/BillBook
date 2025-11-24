<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends CI_Model
{
    /**
     * Get all customers for logged-in user
     */
    public function get_all_customers($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('customers');
        return $query->result();
    }

    /**
     * Get single customer (with user_id check)
     */
    public function get_customer($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('customers');
        return $query->row();
    }

    /**
     * Create customer for logged-in user
     */
    public function create_customer($data, $user_id)
    {
        $data['user_id'] = $user_id;
        return $this->db->insert('customers', $data);
    }

    /**
     * Update customer (only if belongs to user)
     */
    public function update_customer($id, $data, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('customers', $data);
    }

    /**
     * Delete customer (only if belongs to user)
     */
    public function delete_customer($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('customers');
    }

    /**
     * Get customer ledger (only user's invoices)
     */
    public function get_customer_ledger($customer_id, $user_id)
    {
        $this->db->select('invoices.*, 
            (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE invoice_id = invoices.id) as paid_amount');
        $this->db->where('customer_id', $customer_id);
        $this->db->where('invoices.user_id', $user_id);
        $this->db->order_by('invoice_date', 'DESC');
        $query = $this->db->get('invoices');
        return $query->result();
    }
}
