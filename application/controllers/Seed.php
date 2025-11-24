<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seed extends CI_Controller
{

    public function index()
    {
        $this->load->model('Auth_model');

        $email = 'admin@example.com';
        $password = 'password';

        $existing_user = $this->Auth_model->get_user_by_email($email);

        if ($existing_user) {
            echo "User {$email} already exists.<br>";
        } else {
            $data = [
                'name' => 'Admin User',
                'email' => $email,
                'password' => $password,
                'role' => 'admin',
                'email_verified' => 1,
                'provider' => 'email'
            ];

            if ($this->Auth_model->create_staff_user($data)) {
                echo "User {$email} created successfully with password '{$password}'.";
            } else {
                echo "Failed to create user.";
            }
        }
    }
}
