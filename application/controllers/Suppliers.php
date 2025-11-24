<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Suppliers_model');
    }

    public function index()
    {
        $data['title'] = 'Suppliers';
        $user_id = $this->session->userdata('user_id');
        $data['suppliers'] = $this->Suppliers_model->get_all_suppliers($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('suppliers/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Add Supplier';

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');

            if ($this->form_validation->run() == TRUE) {
                $data = [
                    'user_id' => $this->session->userdata('user_id'),
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'gstin' => $this->input->post('gstin')
                ];

                if ($this->Suppliers_model->create_supplier($data)) {
                    $this->session->set_flashdata('success', 'Supplier added successfully!');
                    redirect('suppliers');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add supplier.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('suppliers/create', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Supplier';
        $user_id = $this->session->userdata('user_id');
        $data['supplier'] = $this->Suppliers_model->get_supplier($id, $user_id);

        if (!$data['supplier']) {
            show_404();
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');

            if ($this->form_validation->run() == TRUE) {
                $update_data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'gstin' => $this->input->post('gstin')
                ];

                if ($this->Suppliers_model->update_supplier($id, $user_id, $update_data)) {
                    $this->session->set_flashdata('success', 'Supplier updated successfully!');
                    redirect('suppliers');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update supplier.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('suppliers/edit', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        if ($this->Suppliers_model->delete_supplier($id, $user_id)) {
            $this->session->set_flashdata('success', 'Supplier deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete supplier.');
        }
        redirect('suppliers');
    }
}
