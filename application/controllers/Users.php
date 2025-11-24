<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Only Admin can access User Management
        if ($this->session->userdata('role') !== 'admin') {
            show_error('Access Denied', 403);
        }
        $this->load->model('Auth_model');
    }

    public function index()
    {
        $data['title'] = 'User Management';
        $user_id = $this->session->userdata('user_id');

        // Get staff created by this admin
        $this->db->where('owner_id', $user_id);
        $data['users'] = $this->db->get('users')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('users/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Add New User';

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('role', 'Role', 'required');

            if ($this->form_validation->run()) {
                $user_data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password'), // Will be hashed in model
                    'role' => $this->input->post('role'),
                    'owner_id' => $this->session->userdata('user_id'),
                    'email_verified' => 1,
                    'provider' => 'email'
                ];

                if ($this->Auth_model->create_staff_user($user_data)) {
                    log_activity('Create User', 'Created user: ' . $this->input->post('email'));
                    $this->session->set_flashdata('success', 'User created successfully!');
                    redirect('users');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create user.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('users/create', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        $owner_id = $this->session->userdata('user_id');

        // Verify ownership
        $this->db->where('id', $id);
        $this->db->where('owner_id', $owner_id);
        $user = $this->db->get('users')->row();

        if ($user) {
            $this->db->where('id', $id);
            $this->db->delete('users');
            log_activity('Delete User', 'Deleted user: ' . $user->email);
            $this->session->set_flashdata('success', 'User deleted.');
        } else {
            $this->session->set_flashdata('error', 'User not found or access denied.');
        }
        redirect('users');
    }

    public function logs()
    {
        $data['title'] = 'Activity Logs';
        $owner_id = $this->session->userdata('user_id');

        // Get logs for Admin and their Staff
        // 1. Get all user IDs (Admin + Staff)
        $this->db->select('id');
        $this->db->where('owner_id', $owner_id);
        $this->db->or_where('id', $owner_id);
        $users = $this->db->get('users')->result_array();
        $user_ids = array_column($users, 'id');

        if (!empty($user_ids)) {
            $this->db->select('activity_logs.*, users.name as user_name, users.role');
            $this->db->join('users', 'users.id = activity_logs.user_id');
            $this->db->where_in('activity_logs.user_id', $user_ids);
            $this->db->order_by('activity_logs.created_at', 'DESC');
            $this->db->limit(100);
            $data['logs'] = $this->db->get('activity_logs')->result();
        } else {
            $data['logs'] = [];
        }

        $this->load->view('templates/header', $data);
        $this->load->view('users/logs', $data);
        $this->load->view('templates/footer');
    }
}
