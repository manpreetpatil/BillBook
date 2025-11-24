<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
    }

    public function index()
    {
        $data['title'] = 'Settings';
        $user_id = $this->session->userdata('user_id');
        $data['settings'] = $this->Settings_model->get_settings($user_id);
        $data['currencies'] = $this->Settings_model->get_currencies();

        $this->load->view('templates/header', $data);
        $this->load->view('settings/index', $data);
        $this->load->view('templates/footer');
    }

    public function update()
    {
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('settings');
        } else {
            $user_id = $this->session->userdata('user_id');
            $data = [
                'company_name' => $this->input->post('company_name'),
                'address' => $this->input->post('address'),
                'state' => $this->input->post('state'),
                'gstin' => $this->input->post('gstin'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'currency' => $this->input->post('currency'),
                'invoice_prefix' => $this->input->post('invoice_prefix')
            ];

            if ($this->Settings_model->update_settings($user_id, $data)) {
                $this->session->set_flashdata('success', 'Settings updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update settings.');
            }
            redirect('settings');
        }
    }
}
