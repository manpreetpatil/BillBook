<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Expenses extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Expenses_model');
    }

    public function index()
    {
        $data['title'] = 'Expenses';
        $user_id = $this->session->userdata('user_id');

        $filters = [
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'category_id' => $this->input->get('category_id')
        ];

        $data['expenses'] = $this->Expenses_model->get_all_expenses($user_id, $filters);
        $data['categories'] = $this->Expenses_model->get_categories($user_id);
        $data['filters'] = $filters;

        $this->load->view('templates/header', $data);
        $this->load->view('expenses/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Record Expense';
        $user_id = $this->session->userdata('user_id');

        if ($this->input->post()) {
            $this->form_validation->set_rules('category_id', 'Category', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('expense_date', 'Date', 'required');

            if ($this->form_validation->run()) {
                $expense_data = [
                    'user_id' => $user_id,
                    'category_id' => $this->input->post('category_id'),
                    'amount' => $this->input->post('amount'),
                    'expense_date' => $this->input->post('expense_date'),
                    'description' => $this->input->post('description'),
                    'reference_no' => $this->input->post('reference_no')
                ];

                if ($this->Expenses_model->create_expense($expense_data)) {
                    $this->session->set_flashdata('success', 'Expense recorded successfully!');
                    redirect('expenses');
                } else {
                    $this->session->set_flashdata('error', 'Failed to record expense.');
                }
            }
        }

        $data['categories'] = $this->Expenses_model->get_categories($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('expenses/create', $data);
        $this->load->view('templates/footer');
    }

    public function categories()
    {
        $data['title'] = 'Expense Categories';
        $user_id = $this->session->userdata('user_id');

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Category Name', 'required|trim');

            if ($this->form_validation->run()) {
                $category_data = [
                    'user_id' => $user_id,
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description')
                ];

                if ($this->Expenses_model->create_category($category_data)) {
                    $this->session->set_flashdata('success', 'Category added successfully!');
                    redirect('expenses/categories');
                }
            }
        }

        $data['categories'] = $this->Expenses_model->get_categories($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('expenses/categories', $data);
        $this->load->view('templates/footer');
    }

    public function delete_category($id)
    {
        $user_id = $this->session->userdata('user_id');
        $this->Expenses_model->delete_category($id, $user_id);
        $this->session->set_flashdata('success', 'Category deleted.');
        redirect('expenses/categories');
    }

    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        $this->Expenses_model->delete_expense($id, $user_id);
        $this->session->set_flashdata('success', 'Expense deleted.');
        redirect('expenses');
    }
}
