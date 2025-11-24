<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_reports extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items_model');
    }

    public function stock()
    {
        $data['title'] = 'Stock Report';
        $user_id = $this->session->userdata('user_id');

        // Get items with current stock
        $this->db->where('user_id', $user_id);
        $this->db->order_by('current_stock', 'ASC');
        $data['items'] = $this->db->get('items')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('inventory_reports/stock', $data);
        $this->load->view('templates/footer');
    }

    public function expiry()
    {
        $data['title'] = 'Expiry Report';
        $user_id = $this->session->userdata('user_id');

        // Get batches with expiry dates, joined with items
        $this->db->select('item_batches.*, items.name as item_name');
        $this->db->join('items', 'items.id = item_batches.item_id');
        $this->db->where('items.user_id', $user_id);
        $this->db->where('item_batches.expiry_date IS NOT NULL', null, false);
        $this->db->where('item_batches.quantity >', 0);
        $this->db->order_by('item_batches.expiry_date', 'ASC');
        $data['batches'] = $this->db->get('item_batches')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('inventory_reports/expiry', $data);
        $this->load->view('templates/footer');
    }

    public function movement()
    {
        $data['title'] = 'Stock Movement Report';
        $user_id = $this->session->userdata('user_id');

        // Get logs joined with items
        $this->db->select('inventory_logs.*, items.name as item_name');
        $this->db->join('items', 'items.id = inventory_logs.item_id');
        $this->db->where('inventory_logs.user_id', $user_id);
        $this->db->order_by('inventory_logs.created_at', 'DESC');
        $this->db->limit(100); // Limit to last 100 movements
        $data['logs'] = $this->db->get('inventory_logs')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('inventory_reports/movement', $data);
        $this->load->view('templates/footer');
    }
}
