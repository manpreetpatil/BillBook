<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    /**
     * Get settings for logged-in user
     */
    public function get_settings($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('settings');
        return $query->row();
    }

    /**
     * Update settings for logged-in user
     */
    public function update_settings($user_id, $data)
    {
        // Check if settings exist for this user
        $this->db->where('user_id', $user_id);
        $existing = $this->db->get('settings')->row();

        if (!$existing) {
            // Create new settings for this user
            $data['user_id'] = $user_id;
            return $this->db->insert('settings', $data);
        } else {
            // Update existing settings
            $this->db->where('user_id', $user_id);
            return $this->db->update('settings', $data);
        }
    }
}
