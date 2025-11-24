<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Payments_model');
        $this->load->model('Invoices_model');
    }

    public function index()
    {
        $data['title'] = 'Payments';
        $user_id = $this->session->userdata('user_id');
        $data['payments'] = $this->Payments_model->get_all_payments($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('payments/index', $data);
        $this->load->view('templates/footer');
    }

    public function add($invoice_id = null)
    {
        $data['title'] = 'Add Payment';

        if ($invoice_id) {
            $user_id = $this->session->userdata('user_id');
            $data['invoice'] = $this->Invoices_model->get_invoice($invoice_id, $user_id);
            if (!$data['invoice']) {
                show_404();
            }

            // Calculate remaining balance
            $payments = $this->Payments_model->get_payments_by_invoice($invoice_id);
            $total_paid = 0;
            foreach ($payments as $payment) {
                $total_paid += $payment->amount;
            }
            $data['balance'] = $data['invoice']->grand_total - $total_paid;
        } else {
            // Get all unpaid/partial invoices for this user
            $user_id = $this->session->userdata('user_id');
            $this->db->where('user_id', $user_id);
            $this->db->where_in('status', ['Unpaid', 'Partial']);
            $data['invoices'] = $this->db->get('invoices')->result();
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('invoice_id', 'Invoice', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
            $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');

            if ($this->form_validation->run() == TRUE) {
                $payment_data = [
                    'invoice_id' => $this->input->post('invoice_id'),
                    'payment_date' => $this->input->post('payment_date'),
                    'amount' => $this->input->post('amount'),
                    'payment_method' => $this->input->post('payment_method'),
                    'transaction_id' => $this->input->post('transaction_id'),
                    'notes' => $this->input->post('notes')
                ];

                $user_id = $this->session->userdata('user_id');
                if ($this->Payments_model->create_payment($payment_data, $user_id)) {
                    $this->session->set_flashdata('success', 'Payment recorded successfully!');
                    redirect('invoices/view/' . $this->input->post('invoice_id'));
                } else {
                    $this->session->set_flashdata('error', 'Failed to record payment.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('payments/add', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        $payment = $this->Payments_model->get_payment($id, $user_id);

        if ($this->Payments_model->delete_payment($id, $user_id)) {
            $this->session->set_flashdata('success', 'Payment deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete payment.');
        }

        if ($payment) {
            redirect('invoices/view/' . $payment->invoice_id);
        } else {
            redirect('payments');
        }
    }
}
