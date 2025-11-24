<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchases extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Purchases_model');
        $this->load->model('Suppliers_model');
        $this->load->model('Items_model');
    }

    public function index()
    {
        $data['title'] = 'Purchase Orders';
        $user_id = $this->session->userdata('user_id');
        $data['purchases'] = $this->Purchases_model->get_all_purchases($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('purchases/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Create Purchase Order';
        $user_id = $this->session->userdata('user_id');
        $data['suppliers'] = $this->Suppliers_model->get_all_suppliers($user_id);
        $data['items'] = $this->Items_model->get_all_items($user_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
            $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');

            if ($this->form_validation->run() == TRUE) {
                $items_data = [];
                $item_ids = $this->input->post('item_id');
                $quantities = $this->input->post('quantity');
                $unit_prices = $this->input->post('unit_price');
                $batch_numbers = $this->input->post('batch_number');
                $expiry_dates = $this->input->post('expiry_date');

                $total_amount = 0;

                for ($i = 0; $i < count($item_ids); $i++) {
                    if (!empty($item_ids[$i])) {
                        $qty = floatval($quantities[$i]);
                        $price = floatval($unit_prices[$i]);
                        $total = $qty * $price;
                        $total_amount += $total;

                        $items_data[] = [
                            'item_id' => $item_ids[$i],
                            'quantity' => $qty,
                            'unit_price' => $price,
                            'batch_number' => $batch_numbers[$i],
                            'expiry_date' => !empty($expiry_dates[$i]) ? $expiry_dates[$i] : NULL,
                            'total' => $total
                        ];
                    }
                }

                $purchase_data = [
                    'supplier_id' => $this->input->post('supplier_id'),
                    'purchase_date' => $this->input->post('purchase_date'),
                    'status' => $this->input->post('status'),
                    'total_amount' => $total_amount,
                    'notes' => $this->input->post('notes')
                ];

                if ($this->Purchases_model->create_purchase($purchase_data, $items_data, $user_id)) {
                    $this->session->set_flashdata('success', 'Purchase Order created successfully!');
                    redirect('purchases');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create Purchase Order.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('purchases/create', $data);
        $this->load->view('templates/footer');
    }

    public function view($id)
    {
        $data['title'] = 'View Purchase Order';
        $user_id = $this->session->userdata('user_id');
        $data['purchase'] = $this->Purchases_model->get_purchase($id, $user_id);
        $data['purchase_items'] = $this->Purchases_model->get_purchase_items($id);

        if (!$data['purchase']) {
            show_404();
        }

        $this->load->view('templates/header', $data);
        $this->load->view('purchases/view', $data);
        $this->load->view('templates/footer');
    }
}
