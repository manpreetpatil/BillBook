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
        $data['title'] = 'GST Report (GSTR-1)';
        $user_id = $this->session->userdata('user_id');

        // Get date range
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Get all invoice items with tax details for this user
        $this->db->select('invoice_items.*, invoices.invoice_number, invoices.invoice_date, 
            customers.name as customer_name, customers.gstin, customers.state as customer_state');
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
        $data['total_cgst'] = 0;
        $data['total_sgst'] = 0;
        $data['total_igst'] = 0;
        $data['total_tax'] = 0;

        foreach ($data['items'] as $item) {
            $tax_rate = $item->tax_rate;
            $taxable_amount = $item->quantity * $item->price;

            // Handle inclusive tax calculation for display if needed, 
            // but here we rely on stored values which should be correct.
            // If item was inclusive, price stored is unit price (inclusive? or base?).
            // In Invoices controller we calculated everything. 
            // Let's assume stored values in invoice_items (cgst_amount etc) are correct.

            if (!isset($data['tax_summary'][$tax_rate])) {
                $data['tax_summary'][$tax_rate] = [
                    'taxable_amount' => 0,
                    'cgst_amount' => 0,
                    'sgst_amount' => 0,
                    'igst_amount' => 0,
                    'tax_amount' => 0
                ];
            }

            $data['tax_summary'][$tax_rate]['taxable_amount'] += $taxable_amount;
            $data['tax_summary'][$tax_rate]['cgst_amount'] += $item->cgst_amount;
            $data['tax_summary'][$tax_rate]['sgst_amount'] += $item->sgst_amount;
            $data['tax_summary'][$tax_rate]['igst_amount'] += $item->igst_amount;
            $data['tax_summary'][$tax_rate]['tax_amount'] += $item->tax_amount;

            $data['total_taxable'] += $taxable_amount;
            $data['total_cgst'] += $item->cgst_amount;
            $data['total_sgst'] += $item->sgst_amount;
            $data['total_igst'] += $item->igst_amount;
            $data['total_tax'] += $item->tax_amount;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('reports/gst_report', $data);
        $this->load->view('templates/footer');
    }

    public function gstr3b_report()
    {
        $data['title'] = 'GSTR-3B Summary';
        $user_id = $this->session->userdata('user_id');

        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Outward Supplies (Sales)
        $this->db->select_sum('subtotal', 'taxable_value');
        $this->db->select_sum('igst_amount');
        $this->db->select_sum('cgst_amount');
        $this->db->select_sum('sgst_amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->where('status !=', 'Cancelled');
        $query = $this->db->get('invoices');
        $data['outward_supplies'] = $query->row();

        // Eligible ITC (Purchases) - Assuming we track GST on purchases
        // For now, we might not have GST columns in purchases table, 
        // so we'll just placeholder it or use total_amount if we assume it includes tax.
        // TODO: Add GST columns to purchases table for full ITC tracking.
        $data['itc'] = (object) [
            'igst_amount' => 0,
            'cgst_amount' => 0,
            'sgst_amount' => 0,
            'cess_amount' => 0
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('reports/gstr3b_report', $data);
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

    /**
     * Export GST Report to CSV
     */
    public function gst_report_csv()
    {
        $user_id = $this->session->userdata('user_id');
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        // Get all invoice items with tax details
        $this->db->select('invoice_items.*, invoices.invoice_number, invoices.invoice_date, 
            customers.name as customer_name, customers.gstin, customers.state as customer_state');
        $this->db->join('invoices', 'invoices.id = invoice_items.invoice_id');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->where('invoices.invoice_date >=', $start_date);
        $this->db->where('invoices.invoice_date <=', $end_date);
        $this->db->where('invoices.status !=', 'Cancelled');
        $this->db->order_by('invoices.invoice_date', 'DESC');
        $items = $this->db->get('invoice_items')->result();

        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=gst_report_' . $start_date . '_to_' . $end_date . '.csv');
        $output = fopen('php://output', 'w');

        // Output column headings
        fputcsv($output, ['Date', 'Invoice #', 'Customer', 'GSTIN', 'Place of Supply', 'Taxable Amount', 'Tax Rate', 'CGST', 'SGST', 'IGST', 'Total Tax']);

        foreach ($items as $item) {
            $taxable_amount = $item->quantity * $item->price;
            fputcsv($output, [
                $item->invoice_date,
                $item->invoice_number,
                $item->customer_name,
                $item->gstin ?: 'N/A',
                $item->customer_state ?: 'N/A',
                number_format($taxable_amount, 2, '.', ''),
                $item->tax_rate,
                number_format($item->cgst_amount, 2, '.', ''),
                number_format($item->sgst_amount, 2, '.', ''),
                number_format($item->igst_amount, 2, '.', ''),
                number_format($item->tax_amount, 2, '.', '')
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Export GSTR-3B Report to CSV
     */
    public function gstr3b_report_csv()
    {
        $user_id = $this->session->userdata('user_id');
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        // Get outward supplies data
        $this->db->select_sum('subtotal', 'taxable_value');
        $this->db->select_sum('igst_amount');
        $this->db->select_sum('cgst_amount');
        $this->db->select_sum('sgst_amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->where('status !=', 'Cancelled');
        $outward = $this->db->get('invoices')->row();

        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=gstr3b_report_' . $start_date . '_to_' . $end_date . '.csv');
        $output = fopen('php://output', 'w');

        // Output GSTR-3B summary
        fputcsv($output, ['GSTR-3B Summary Report']);
        fputcsv($output, ['Period', $start_date . ' to ' . $end_date]);
        fputcsv($output, []);
        fputcsv($output, ['Description', 'Taxable Value', 'IGST', 'CGST', 'SGST', 'Total Tax']);

        $total_tax = ($outward->igst_amount ?: 0) + ($outward->cgst_amount ?: 0) + ($outward->sgst_amount ?: 0);
        fputcsv($output, [
            'Outward Supplies',
            number_format($outward->taxable_value ?: 0, 2, '.', ''),
            number_format($outward->igst_amount ?: 0, 2, '.', ''),
            number_format($outward->cgst_amount ?: 0, 2, '.', ''),
            number_format($outward->sgst_amount ?: 0, 2, '.', ''),
            number_format($total_tax, 2, '.', '')
        ]);

        fclose($output);
        exit;
    }

    /**
     * Export Outstanding Report to CSV
     */
    public function outstanding_csv()
    {
        $user_id = $this->session->userdata('user_id');

        // Get outstanding invoices
        $this->db->select('invoices.*, customers.name as customer_name');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->where('invoices.user_id', $user_id);
        $this->db->where_in('invoices.status', ['Unpaid', 'Partial']);
        $this->db->order_by('invoices.due_date', 'ASC');
        $invoices = $this->db->get('invoices')->result();

        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=outstanding_report_' . date('Ymd') . '.csv');
        $output = fopen('php://output', 'w');

        // Output column headings
        fputcsv($output, ['Invoice #', 'Date', 'Due Date', 'Customer', 'Total Amount', 'Paid Amount', 'Balance', 'Days Overdue', 'Status']);

        foreach ($invoices as $invoice) {
            // Calculate paid amount
            $this->db->select('SUM(amount) as paid');
            $this->db->where('invoice_id', $invoice->id);
            $paid_row = $this->db->get('payments')->row();
            $paid_amount = $paid_row->paid ?: 0;
            $balance = $invoice->grand_total - $paid_amount;

            // Calculate days overdue
            $days_overdue = 0;
            if ($invoice->due_date) {
                $due = new DateTime($invoice->due_date);
                $today = new DateTime();
                if ($today > $due) {
                    $days_overdue = $today->diff($due)->days;
                }
            }

            fputcsv($output, [
                $invoice->invoice_number,
                $invoice->invoice_date,
                $invoice->due_date ?: 'N/A',
                $invoice->customer_name,
                number_format($invoice->grand_total, 2, '.', ''),
                number_format($paid_amount, 2, '.', ''),
                number_format($balance, 2, '.', ''),
                $days_overdue,
                $invoice->status
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Export Profit/Loss Report to CSV
     */
    public function profit_loss_csv()
    {
        $user_id = $this->session->userdata('user_id');
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');

        // Calculate sales
        $this->db->select_sum('grand_total', 'total_sales');
        $this->db->where('user_id', $user_id);
        $this->db->where('invoice_date >=', $start_date);
        $this->db->where('invoice_date <=', $end_date);
        $this->db->where('status !=', 'Cancelled');
        $sales = $this->db->get('invoices')->row();
        $total_sales = $sales->total_sales ?: 0;

        // Calculate expenses
        $total_expenses = $this->Expenses_model->get_total_expenses($user_id, $start_date, $end_date);

        // Calculate profit
        $gross_profit = $total_sales;
        $net_profit = $total_sales - $total_expenses;
        $profit_margin = $total_sales > 0 ? ($net_profit / $total_sales) * 100 : 0;

        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=profit_loss_report_' . $start_date . '_to_' . $end_date . '.csv');
        $output = fopen('php://output', 'w');

        // Output profit/loss summary
        fputcsv($output, ['Profit & Loss Report']);
        fputcsv($output, ['Period', $start_date . ' to ' . $end_date]);
        fputcsv($output, []);
        fputcsv($output, ['Description', 'Amount']);
        fputcsv($output, ['Total Sales', number_format($total_sales, 2, '.', '')]);
        fputcsv($output, ['Total Expenses', number_format($total_expenses, 2, '.', '')]);
        fputcsv($output, ['Gross Profit', number_format($gross_profit, 2, '.', '')]);
        fputcsv($output, ['Net Profit', number_format($net_profit, 2, '.', '')]);
        fputcsv($output, ['Profit Margin (%)', number_format($profit_margin, 2, '.', '')]);

        fclose($output);
        exit;
    }
}

