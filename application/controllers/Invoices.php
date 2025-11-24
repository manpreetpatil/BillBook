<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Invoices_model');
        $this->load->model('Customers_model');
        $this->load->model('Items_model');
        $this->load->helper('permission');
    }

    public function index()
    {
        $data['title'] = 'Invoices';
        $user_id = $this->session->userdata('user_id');
        $data['invoices'] = $this->Invoices_model->get_all_invoices($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('invoices/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        if (!check_permission('create_invoice')) {
            show_error('Access Denied', 403);
        }

        $data['title'] = 'Create Invoice';
        $user_id = $this->session->userdata('user_id');
        $data['customers'] = $this->Customers_model->get_all_customers($user_id);
        $data['items'] = $this->Items_model->get_all_items($user_id);
        $data['invoice_number'] = $this->Invoices_model->generate_invoice_number($user_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('customer_id', 'Customer', 'required');
            $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');

            if ($this->form_validation->run() == TRUE) {
                // Calculate totals
                $subtotal = 0;
                $tax_total = 0;

                $items_data = [];
                $item_names = $this->input->post('item_name');
                $quantities = $this->input->post('quantity');
                $prices = $this->input->post('price');
                $tax_rates = $this->input->post('tax_rate');

                for ($i = 0; $i < count($item_names); $i++) {
                    if (!empty($item_names[$i])) {
                        $qty = floatval($quantities[$i]);
                        $price = floatval($prices[$i]);
                        $tax_rate = floatval($tax_rates[$i]);

                        $item_total = $qty * $price;
                        $tax_amount = ($item_total * $tax_rate) / 100;

                        $subtotal += $item_total;
                        $tax_total += $tax_amount;

                        $items_data[] = [
                            'item_name' => $item_names[$i],
                            'description' => $this->input->post('description')[$i],
                            'quantity' => $qty,
                            'price' => $price,
                            'tax_rate' => $tax_rate,
                            'tax_amount' => $tax_amount,
                            'total' => $item_total + $tax_amount
                        ];
                    }
                }

                $invoice_data = [
                    'invoice_number' => $this->input->post('invoice_number'),
                    'customer_id' => $this->input->post('customer_id'),
                    'invoice_date' => $this->input->post('invoice_date'),
                    'due_date' => $this->input->post('due_date'),
                    'subtotal' => $subtotal,
                    'tax_total' => $tax_total,
                    'grand_total' => $subtotal + $tax_total,
                    'notes' => $this->input->post('notes')
                ];

                $invoice_id = $this->Invoices_model->create_invoice($invoice_data, $items_data, $user_id);

                if ($invoice_id) {
                    log_activity('Create Invoice', 'Created Invoice #' . $this->input->post('invoice_number'));
                    $this->session->set_flashdata('success', 'Invoice created successfully!');
                    redirect('invoices/view/' . $invoice_id);
                } else {
                    $this->session->set_flashdata('error', 'Failed to create invoice.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('invoices/create', $data);
        $this->load->view('templates/footer');
    }

    public function view($id)
    {
        $data['title'] = 'View Invoice';
        $user_id = $this->session->userdata('user_id');
        $data['invoice'] = $this->Invoices_model->get_invoice($id, $user_id);
        $data['invoice_items'] = $this->Invoices_model->get_invoice_items($id);

        if (!$data['invoice']) {
            show_404();
        }

        // Get payments for this invoice
        $this->load->model('Payments_model');
        $data['payments'] = $this->Payments_model->get_payments_by_invoice($id);

        $this->load->view('templates/header', $data);
        $this->load->view('invoices/view', $data);
        $this->load->view('templates/footer');
    }

    public function print_invoice($id)
    {
        $user_id = $this->session->userdata('user_id');
        $data['invoice'] = $this->Invoices_model->get_invoice($id, $user_id);
        $data['invoice_items'] = $this->Invoices_model->get_invoice_items($id);

        if (!$data['invoice']) {
            show_404();
        }

        $this->load->model('Settings_model');
        $data['settings'] = $this->Settings_model->get_settings($user_id);

        $this->load->view('invoices/print', $data);
    }

    public function delete($id)
    {
        if (!check_permission('delete_invoice')) {
            $this->session->set_flashdata('error', 'Access Denied: You do not have permission to delete invoices.');
            redirect('invoices');
        }

        $user_id = $this->session->userdata('user_id');
        if ($this->Invoices_model->delete_invoice($id, $user_id)) {
            log_activity('Delete Invoice', 'Deleted Invoice ID: ' . $id);
            $this->session->set_flashdata('success', 'Invoice deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete invoice.');
        }
        redirect('invoices');
    }
    public function share($id)
    {
        $user_id = $this->session->userdata('user_id');
        $invoice = $this->Invoices_model->get_invoice($id, $user_id);

        if (!$invoice) {
            // Return JSON error instead of 404 HTML
            echo json_encode(['error' => 'Invoice not found']);
            return;
        }

        // Generate hash if not exists
        if (empty($invoice->share_hash)) {
            $hash = bin2hex(random_bytes(32));
            $this->Invoices_model->update_share_hash($id, $hash);
            $invoice->share_hash = $hash;
        }

        $data['invoice'] = $invoice;
        $data['share_link'] = site_url('invoices/public_view/' . $invoice->share_hash);

        // Return JSON for modal
        ob_clean(); // Clean any previous output
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'share_link' => $data['share_link'],
            'whatsapp_link' => 'https://wa.me/?text=' . urlencode('Here is your invoice: ' . $data['share_link']),
            'email_subject' => 'Invoice #' . $invoice->invoice_number,
            'email_body' => 'Here is your invoice: ' . $data['share_link']
        ]);
    }

    public function public_view($hash)
    {
        $data['invoice'] = $this->Invoices_model->get_invoice_by_hash($hash);

        if (!$data['invoice']) {
            show_404();
        }

        $data['invoice_items'] = $this->Invoices_model->get_invoice_items($data['invoice']->id);
        $data['title'] = 'Invoice #' . $data['invoice']->invoice_number;

        // Load settings for the invoice owner
        $this->load->model('Settings_model');
        $data['settings'] = $this->Settings_model->get_settings($data['invoice']->user_id);

        $this->load->view('invoices/public_view', $data);
    }

    public function print_thermal($id)
    {
        $user_id = $this->session->userdata('user_id');
        $data['invoice'] = $this->Invoices_model->get_invoice($id, $user_id);

        if (!$data['invoice']) {
            show_404();
        }

        $data['invoice_items'] = $this->Invoices_model->get_invoice_items($id);
        $this->load->model('Settings_model');
        $data['settings'] = $this->Settings_model->get_settings($user_id);

        $this->load->view('invoices/print_thermal', $data);
    }
}
