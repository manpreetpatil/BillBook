<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items_model');
    }

    public function index()
    {
        $data['title'] = 'Items';
        $user_id = $this->session->userdata('user_id');
        $data['items'] = $this->Items_model->get_all_items($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Add New Item';

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Item Name', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric');

            if ($this->form_validation->run() == TRUE) {
                $item_data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'unit' => $this->input->post('unit'),
                    'price' => $this->input->post('price'),
                    'hsn_sac' => $this->input->post('hsn_sac'),
                    'tax_rate' => $this->input->post('tax_rate')
                ];

                $user_id = $this->session->userdata('user_id');
                if ($this->Items_model->create_item($item_data, $user_id)) {
                    $this->session->set_flashdata('success', 'Item created successfully!');
                    redirect('items');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create item.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('items/create', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Item';
        $user_id = $this->session->userdata('user_id');
        $data['item'] = $this->Items_model->get_item($id, $user_id);

        if (!$data['item']) {
            show_404();
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Item Name', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric');

            if ($this->form_validation->run() == TRUE) {
                $item_data = [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'unit' => $this->input->post('unit'),
                    'price' => $this->input->post('price'),
                    'hsn_sac' => $this->input->post('hsn_sac'),
                    'tax_rate' => $this->input->post('tax_rate')
                ];

                $user_id = $this->session->userdata('user_id');
                if ($this->Items_model->update_item($id, $item_data, $user_id)) {
                    $this->session->set_flashdata('success', 'Item updated successfully!');
                    redirect('items');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update item.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('items/edit', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        if ($this->Items_model->delete_item($id, $user_id)) {
            $this->session->set_flashdata('success', 'Item deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete item.');
        }
        redirect('items');
    }
}
