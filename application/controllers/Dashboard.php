<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['title'] = 'Dashboard';
        $user_id = $this->session->userdata('user_id');

        $this->load->model('Expenses_model');
        $this->load->model('Items_model');

        // Get total sales for this user
        $this->db->select('SUM(grand_total) as total');
        $this->db->where('user_id', $user_id);
        $this->db->where('status !=', 'Cancelled');
        $total_sales = $this->db->get('invoices')->row();

        // Get total paid for this user
        $this->db->select('SUM(amount) as total');
        $this->db->where('user_id', $user_id);
        $total_paid = $this->db->get('payments')->row();

        // Get due amount (unpaid + partial) for this user
        $this->db->select('SUM(grand_total) as total');
        $this->db->where('user_id', $user_id);
        $this->db->where_in('status', ['Unpaid', 'Partial']);
        $due_invoices = $this->db->get('invoices')->row();

        // Get paid amounts for partial invoices
        $this->db->select('invoices.id, invoices.grand_total, COALESCE(SUM(payments.amount), 0) as paid');
        $this->db->from('invoices');
        $this->db->join('payments', 'payments.invoice_id = invoices.id', 'left');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->where_in('invoices.status', ['Unpaid', 'Partial']);
        $this->db->group_by('invoices.id');
        $partial_invoices = $this->db->get()->result();

        $due_amount = 0;
        foreach ($partial_invoices as $inv) {
            $due_amount += ($inv->grand_total - $inv->paid);
        }

        // Get customer count for this user
        $this->db->where('user_id', $user_id);
        $total_customers = $this->db->count_all_results('customers');

        // Get invoice count for this user
        $this->db->where('user_id', $user_id);
        $total_invoices = $this->db->count_all_results('invoices');

        // Get total expenses
        $total_expenses = $this->Expenses_model->get_total_expenses($user_id);

        // Get low stock count
        $low_stock_count = $this->Items_model->get_low_stock_count($user_id);

        $data['stats'] = [
            'total_sales' => $total_sales->total ?: 0,
            'due_amount' => $due_amount,
            'total_customers' => $total_customers,
            'total_invoices' => $total_invoices,
            'total_expenses' => $total_expenses,
            'net_profit' => ($total_sales->total ?: 0) - $total_expenses,
            'low_stock_count' => $low_stock_count
        ];

        // Get recent invoices for this user
        $this->db->select('invoices.*, customers.name as customer_name');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->order_by('invoices.created_at', 'DESC');
        $this->db->limit(10);
        $data['recent_invoices'] = $this->db->get('invoices')->result();

        // Get recent expenses
        $this->db->select('expenses.*, expense_categories.name as category_name');
        $this->db->join('expense_categories', 'expense_categories.id = expenses.category_id');
        $this->db->where('expenses.user_id', $user_id);
        $this->db->order_by('expenses.expense_date', 'DESC');
        $this->db->limit(5);
        $data['recent_expenses'] = $this->db->get('expenses')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
