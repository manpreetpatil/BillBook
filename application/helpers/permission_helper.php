<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('check_permission')) {
    function check_permission($action)
    {
        $CI = &get_instance();
        $role = $CI->session->userdata('role');

        // Admin has full access
        if ($role === 'admin') {
            return true;
        }

        // Cashier permissions
        if ($role === 'cashier') {
            $allowed = ['create_invoice', 'view_invoice', 'create_customer', 'view_customer', 'view_item'];
            return in_array($action, $allowed);
        }

        // Staff permissions (View only)
        if ($role === 'staff') {
            $allowed = ['view_invoice', 'view_customer', 'view_item'];
            return in_array($action, $allowed);
        }

        return false;
    }
}

if (!function_exists('log_activity')) {
    function log_activity($action, $details = null)
    {
        $CI = &get_instance();
        $user_id = $CI->session->userdata('user_id');

        // If logged in as staff, use the actual user ID, not the owner ID
        // But wait, session 'user_id' should be the actual user ID.
        // The 'owner_id' is used for data scoping.

        if ($user_id) {
            $data = [
                'user_id' => $user_id,
                'action' => $action,
                'details' => $details,
                'ip_address' => $CI->input->ip_address()
            ];
            $CI->db->insert('activity_logs', $data);
        }
    }
}
