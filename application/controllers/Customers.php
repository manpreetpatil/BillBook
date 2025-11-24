<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customers_model');
    }

    public function index()
    {
        $data['title'] = 'Customers';
        $user_id = $this->session->userdata('user_id');
        $data['customers'] = $this->Customers_model->get_all_customers($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('customers/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Add New Customer';

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Customer Name', 'required');

            if ($this->form_validation->run() == TRUE) {
                $customer_data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'gstin' => $this->input->post('gstin')
                ];

                $user_id = $this->session->userdata('user_id');
                if ($this->Customers_model->create_customer($customer_data, $user_id)) {
                    $this->session->set_flashdata('success', 'Customer created successfully!');
                    redirect('customers');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create customer.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('customers/create', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Customer';
        $user_id = $this->session->userdata('user_id');
        $data['customer'] = $this->Customers_model->get_customer($id, $user_id);

        if (!$data['customer']) {
            show_404();
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Customer Name', 'required');

            if ($this->form_validation->run() == TRUE) {
                $customer_data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'gstin' => $this->input->post('gstin')
                ];

                $user_id = $this->session->userdata('user_id');
                if ($this->Customers_model->update_customer($id, $customer_data, $user_id)) {
                    $this->session->set_flashdata('success', 'Customer updated successfully!');
                    redirect('customers');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update customer.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('customers/edit', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        if ($this->Customers_model->delete_customer($id, $user_id)) {
            $this->session->set_flashdata('success', 'Customer deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete customer.');
        }
        redirect('customers');
    }

    public function ledger($id)
    {
        $data['title'] = 'Customer Ledger';
        $user_id = $this->session->userdata('user_id');
        $data['customer'] = $this->Customers_model->get_customer($id, $user_id);
        $data['ledger'] = $this->Customers_model->get_customer_ledger($id, $user_id);

        if (!$data['customer']) {
            show_404();
        }

        $this->load->view('templates/header', $data);
        $this->load->view('customers/ledger', $data);
        $this->load->view('templates/footer');
    }
}
