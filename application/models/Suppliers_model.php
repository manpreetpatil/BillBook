<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers_model extends CI_Model
{
    public function get_all_suppliers($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('suppliers')->result();
    }

    public function get_supplier($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->get('suppliers')->row();
    }

    public function create_supplier($data)
    {
        $this->db->insert('suppliers', $data);
        return $this->db->insert_id();
    }

    public function update_supplier($id, $user_id, $data)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('suppliers', $data);
    }

    public function delete_supplier($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('suppliers');
    }
}
