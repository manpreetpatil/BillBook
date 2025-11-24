<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    /**
     * Create or update user from Firebase data
     */
    public function create_or_update_user($firebase_uid, $data)
    {
        // Check if user exists
        $existing = $this->get_user_by_firebase_uid($firebase_uid);

        if ($existing) {
            // Update existing user
            $this->db->where('firebase_uid', $firebase_uid);
            return $this->db->update('users', $data);
        } else {
            // Create new user (Admin by default for Firebase login)
            $data['firebase_uid'] = $firebase_uid;
            $data['role'] = 'admin'; // Default role for Firebase users
            return $this->db->insert('users', $data);
        }
    }

    /**
     * Create a staff user
     */
    public function create_staff_user($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        return $this->db->insert('users', $data);
    }

    /**
     * Verify password for staff login
     */
    public function verify_password($email, $password)
    {
        $user = $this->get_user_by_email($email);
        if ($user && $user->password_hash) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Get user by Firebase UID
     */
    public function get_user_by_firebase_uid($firebase_uid)
    {
        $this->db->where('firebase_uid', $firebase_uid);
        return $this->db->get('users')->row();
    }

    /**
     * Get user by email
     */
    public function get_user_by_email($email)
    {
        $this->db->where('email', $email);
        return $this->db->get('users')->row();
    }

    /**
     * Get user by ID
     */
    public function get_user_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('users')->row();
    }

    /**
     * Update user profile
     */
    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Update user by Firebase UID
     */
    public function update_user_by_firebase_uid($firebase_uid, $data)
    {
        $this->db->where('firebase_uid', $firebase_uid);
        return $this->db->update('users', $data);
    }

    /**
     * Verify Firebase ID token using REST API
     */
    public function verify_firebase_token($idToken)
    {
        $this->config->load('firebase');
        $firebase_config = $this->config->item('firebase');

        // Firebase token verification endpoint
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=" . $firebase_config['apiKey'];

        $data = json_encode(['idToken' => $idToken]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return false;
        }

        $result = json_decode($response, true);

        if (isset($result['users']) && count($result['users']) > 0) {
            return $result['users'][0];
        }

        return false;
    }
}
