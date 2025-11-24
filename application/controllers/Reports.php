<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Invoices_model');
        $this->load->model('Payments_model');
        $this->load->model('Purchases_model');
        $this->load->model('Expenses_model');
    }

    public function index()
    {
        $data['title'] = 'Reports';
        $user_id = $this->session->userdata('user_id');

        // Get date range from query params or default to current month
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Sales Report for this user
        $this->db->select('invoices.*, customers.name as customer_name');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->order_by('invoice_date', 'DESC');
        $data['invoices'] = $this->db->get('invoices')->result();

        // Calculate totals
        $data['total_sales'] = 0;
        $data['total_tax'] = 0;
        $data['total_paid'] = 0;
        $data['total_due'] = 0;

        foreach ($data['invoices'] as $invoice) {
            $data['total_sales'] += $invoice->grand_total;
            $data['total_tax'] += $invoice->tax_total;

            // Get payments for this invoice
            $this->db->select('SUM(amount) as paid');
            $this->db->where('invoice_id', $invoice->id);
            $paid_row = $this->db->get('payments')->row();
            $paid_amount = $paid_row->paid ?: 0;
            $invoice->paid_amount = $paid_amount;

            $data['total_paid'] += $paid_amount;
            $data['total_due'] += ($invoice->grand_total - $paid_amount);
        }

        $this->load->view('templates/header', $data);
        $this->load->view('reports/index', $data);
        $this->load->view('templates/footer');
    }
    public function export_csv()
    {
        $user_id = $this->session->userdata('user_id');
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        // Fetch same invoice data as index()
        $this->db->select('invoices.*, customers.name as customer_name');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->order_by('invoice_date', 'DESC');
        $invoices = $this->db->get('invoices')->result();

        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=report_' . date('Ymd_His') . '.csv');
        $output = fopen('php://output', 'w');
        // Output column headings
        fputcsv($output, ['Invoice #', 'Date', 'Customer', 'Total', 'Tax', 'Paid', 'Balance', 'Status']);
        foreach ($invoices as $invoice) {
            // Calculate paid amount
            $this->db->select('SUM(amount) as paid');
            $this->db->where('invoice_id', $invoice->id);
            $paid_row = $this->db->get('payments')->row();
            $paid_amount = $paid_row->paid ?: 0;
            $balance = $invoice->grand_total - $paid_amount;
            fputcsv($output, [
                $invoice->invoice_number,
                $invoice->invoice_date,
                $invoice->customer_name,
                $invoice->grand_total,
                $invoice->tax_total,
                $paid_amount,
                $balance,
                $invoice->status
            ]);
        }
        fclose($output);
        exit;
    }

    public function gst_report()
    {
        $data['title'] = 'GST Report';
        $user_id = $this->session->userdata('user_id');

        // Get date range
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Get all invoice items with tax details for this user
        $this->db->select('invoice_items.*, invoices.invoice_number, invoices.invoice_date, 
            customers.name as customer_name, customers.gstin');
        $this->db->join('invoices', 'invoices.id = invoice_items.invoice_id');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->where('invoices.invoice_date >=', $start_date);
        $this->db->where('invoices.invoice_date <=', $end_date);
        $this->db->where('invoices.status !=', 'Cancelled');
        $this->db->order_by('invoices.invoice_date', 'DESC');
        $data['items'] = $this->db->get('invoice_items')->result();

        // Group by tax rate
        $data['tax_summary'] = [];
        $data['total_taxable'] = 0;
        $data['total_tax'] = 0;

        foreach ($data['items'] as $item) {
            $tax_rate = $item->tax_rate;
            $taxable_amount = $item->quantity * $item->price;

            if (!isset($data['tax_summary'][$tax_rate])) {
                $data['tax_summary'][$tax_rate] = [
                    'taxable_amount' => 0,
                    'tax_amount' => 0
                ];
            }

            $data['tax_summary'][$tax_rate]['taxable_amount'] += $taxable_amount;
            $data['tax_summary'][$tax_rate]['tax_amount'] += $item->tax_amount;

            $data['total_taxable'] += $taxable_amount;
            $data['total_tax'] += $item->tax_amount;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('reports/gst_report', $data);
        $this->load->view('templates/footer');
    }

    public function outstanding()
    {
        $data['title'] = 'Outstanding Payments';
        $user_id = $this->session->userdata('user_id');

        // Get all unpaid and partial invoices for this user
        $this->db->select('invoices.*, customers.name as customer_name, customers.phone, customers.email');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->where_in('invoices.status', ['Unpaid', 'Partial']);
        $this->db->order_by('invoices.due_date', 'ASC');
        $data['invoices'] = $this->db->get('invoices')->result();

        $data['total_outstanding'] = 0;

        foreach ($data['invoices'] as $invoice) {
            // Get paid amount
            $this->db->select('SUM(amount) as paid');
            $this->db->where('invoice_id', $invoice->id);
            $paid = $this->db->get('payments')->row();
            $invoice->paid_amount = $paid->paid ?: 0;
            $invoice->balance = $invoice->grand_total - $invoice->paid_amount;

            $data['total_outstanding'] += $invoice->balance;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('reports/outstanding', $data);
        $this->load->view('templates/footer');
    }
    public function profit_loss()
    {
        $data['title'] = 'Profit & Loss Report';
        $user_id = $this->session->userdata('user_id');

        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // 1. Total Income (Sales)
        // We use Grand Total of Invoices (Accrual Basis) or Payments (Cash Basis)?
        // Let's stick to Accrual (Invoiced Amount) for P&L usually, but user might prefer Cash.
        // For simplicity and consistency with "Sales", we use Invoice Grand Totals.
        $this->db->select_sum('grand_total');
        $this->db->where('user_id', $user_id);
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->where('status !=', 'Cancelled');
        $query = $this->db->get('invoices');
        $data['total_income'] = $query->row()->grand_total ?: 0;

        // 2. Total Purchases (Inventory)
        $this->db->select_sum('total_amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('purchase_date >=', $start_date);
        $this->db->where('purchase_date <=', $end_date);
        $this->db->where('status !=', 'Cancelled');
        $query = $this->db->get('purchases');
        $data['total_purchases'] = $query->row()->total_amount ?: 0;

        // 3. Total Expenses (Overhead)
        $data['total_expenses'] = $this->Expenses_model->get_total_expenses($user_id, $start_date, $end_date);

        // 4. Net Profit
        $data['net_profit'] = $data['total_income'] - $data['total_purchases'] - $data['total_expenses'];

        $this->load->view('templates/header', $data);
        $this->load->view('reports/profit_loss', $data);
        $this->load->view('templates/footer');
    }
}
