<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->config->load('firebase');
        $this->load->helper('permission'); // Load permission helper
    }

    /**
     * Login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $data['title'] = 'Login';
        $data['firebase_config'] = $this->config->item('firebase');
        $this->load->view('auth/login', $data);
    }

    public function login_staff()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->Auth_model->verify_password($email, $password);

            if ($user) {
                // Set session data
                $session_data = [
                    'user_id' => $user->id, // This user's ID
                    'owner_id' => $user->owner_id, // The Admin's ID (for data scoping)
                    'role' => $user->role,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);
                log_activity('Login', 'Staff logged in');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password');
            }
        }

        $data['title'] = 'Staff Login';
        $this->load->view('auth/login_staff', $data);
    }

    /**
     * Register page
     */
    public function register()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $data['title'] = 'Register';
        $data['firebase_config'] = $this->config->item('firebase');
        $this->load->view('auth/register', $data);
    }

    /**
     * Verify Firebase token and create session
     * Called via AJAX from frontend
     */
    public function verify_token()
    {
        header('Content-Type: application/json');

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['idToken'])) {
            echo json_encode(['success' => false, 'message' => 'No token provided']);
            return;
        }

        $idToken = $input['idToken'];
        $displayName = isset($input['displayName']) ? $input['displayName'] : null;

        // Verify token with Firebase
        $firebaseUser = $this->Auth_model->verify_firebase_token($idToken);

        if (!$firebaseUser) {
            echo json_encode(['success' => false, 'message' => 'Invalid token']);
            return;
        }

        // Extract user data from Firebase response
        $firebase_uid = $firebaseUser['localId'];
        $email = $firebaseUser['email'];
        $name = $displayName ?? ($firebaseUser['displayName'] ?? 'User');
        $photo_url = $firebaseUser['photoUrl'] ?? null;
        $email_verified = $firebaseUser['emailVerified'] ?? false;

        // Determine provider
        $provider = 'email';
        if (isset($firebaseUser['providerUserInfo']) && count($firebaseUser['providerUserInfo']) > 0) {
            $providerId = $firebaseUser['providerUserInfo'][0]['providerId'];
            if ($providerId === 'google.com') {
                $provider = 'google';
            }
        }

        // Create or update user in database
        $user_data = [
            'name' => $name,
            'email' => $email,
            'photo_url' => $photo_url,
            'email_verified' => $email_verified ? 1 : 0,
            'provider' => $provider
        ];

        $this->Auth_model->create_or_update_user($firebase_uid, $user_data);

        // Get user from database
        $user = $this->Auth_model->get_user_by_firebase_uid($firebase_uid);

        if ($user) {
            // Set session data
            $session_data = [
                'user_id' => $user->id,
                'owner_id' => $user->id, // Admin is their own owner
                'role' => $user->role ?? 'admin', // Default to admin if not set
                'firebase_uid' => $user->firebase_uid,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_photo' => $user->photo_url,
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session_data);
            log_activity('Login', 'Admin logged in via Firebase');

            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create user']);
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'firebase_uid', 'user_name', 'user_email', 'user_photo', 'logged_in']);
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('auth/login');
    }
}
