<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->get_logged_user();

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim');

            if ($this->form_validation->run() == TRUE) {
                $update_data = [
                    'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone')
                ];

                if ($this->Auth_model->update_user($this->session->userdata('user_id'), $update_data)) {
                    // Update session data
                    $this->session->set_userdata('user_name', $update_data['name']);
                    $this->session->set_flashdata('success', 'Profile updated successfully!');
                    redirect('profile');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update profile.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('profile/index', $data);
        $this->load->view('templates/footer');
    }
}
