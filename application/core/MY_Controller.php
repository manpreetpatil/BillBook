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

        // Load settings and make currency available globally
        $this->load->model('Settings_model');
        $settings = $this->Settings_model->get_settings($this->session->userdata('user_id'));

        $currency_symbol = '₹'; // Default
        if ($settings && !empty($settings->currency)) {
            $currency_symbol = $settings->currency;
            // Extract symbol if format is like "USD ($)"
            if (preg_match('/\((.*?)\)/', $currency_symbol, $match)) {
                $currency_symbol = $match[1];
            }
        }

        $this->load->vars(['currency_symbol' => $currency_symbol]);
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
