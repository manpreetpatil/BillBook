<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items_model extends CI_Model
{
    /**
     * Get all items for logged-in user
     */
    public function get_all_items($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('items');
        return $query->result();
    }

    /**
     * Get single item (with user_id check)
     */
    public function get_item($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('items');
        return $query->row();
    }

    /**
     * Create item for logged-in user
     */
    public function create_item($data, $user_id)
    {
        $data['user_id'] = $user_id;
        return $this->db->insert('items', $data);
    }

    /**
     * Update item (only if belongs to user)
     */
    public function update_item($id, $data, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('items', $data);
    }

    /**
     * Delete item (only if belongs to user)
     */
    public function delete_item($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('items');
    }
}
