<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller with Authentication
 * All controllers should extend this to enforce authentication
 */
class MY_Controller extends CI_Controller
{

    protected $logged_user;

    public function __construct()
    {
        parent::__construct();

        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            // Redirect to login page
            redirect('auth/login');
        }

        // Load user data
        $this->logged_user = $this->get_logged_user();
    }

    protected function get_logged_user()
    {
        $user_id = $this->session->userdata('user_id');
        if ($user_id) {
            $this->load->model('Auth_model');
            return $this->Auth_model->get_user_by_id($user_id);
        }
        return null;
    }
}
