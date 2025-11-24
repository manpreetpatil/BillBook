<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_gst extends CI_Controller
{

    public function index()
    {
        $this->load->dbforge();

        // 1. Add 'state' to 'settings' table
        if (!$this->db->field_exists('state', 'settings')) {
            $fields = [
                'state' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => NULL, 'after' => 'address']
            ];
            $this->dbforge->add_column('settings', $fields);
            echo "Added 'state' to 'settings' table.<br>";
        }

        // 2. Add 'state' to 'customers' table
        if (!$this->db->field_exists('state', 'customers')) {
            $fields = [
                'state' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => NULL, 'after' => 'address']
            ];
            $this->dbforge->add_column('customers', $fields);
            echo "Added 'state' to 'customers' table.<br>";
        }

        // 3. Add 'tax_type' to 'items' table
        if (!$this->db->field_exists('tax_type', 'items')) {
            $fields = [
                'tax_type' => ['type' => 'ENUM("exclusive","inclusive")', 'default' => 'exclusive', 'after' => 'tax_rate']
            ];
            $this->dbforge->add_column('items', $fields);
            echo "Added 'tax_type' to 'items' table.<br>";
        }

        // 4. Add GST columns to 'invoices' table
        $invoice_fields = [];
        if (!$this->db->field_exists('cgst_amount', 'invoices')) {
            $invoice_fields['cgst_amount'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00', 'after' => 'tax_total'];
        }
        if (!$this->db->field_exists('sgst_amount', 'invoices')) {
            $invoice_fields['sgst_amount'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00', 'after' => 'cgst_amount'];
        }
        if (!$this->db->field_exists('igst_amount', 'invoices')) {
            $invoice_fields['igst_amount'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00', 'after' => 'sgst_amount'];
        }
        if (!empty($invoice_fields)) {
            $this->dbforge->add_column('invoices', $invoice_fields);
            echo "Added GST columns to 'invoices' table.<br>";
        }

        // 5. Add GST columns to 'invoice_items' table
        $item_fields = [];
        if (!$this->db->field_exists('cgst_rate', 'invoice_items')) {
            $item_fields['cgst_rate'] = ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00', 'after' => 'tax_rate'];
        }
        if (!$this->db->field_exists('cgst_amount', 'invoice_items')) {
            $item_fields['cgst_amount'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00', 'after' => 'cgst_rate'];
        }
        if (!$this->db->field_exists('sgst_rate', 'invoice_items')) {
            $item_fields['sgst_rate'] = ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00', 'after' => 'cgst_amount'];
        }
        if (!$this->db->field_exists('sgst_amount', 'invoice_items')) {
            $item_fields['sgst_amount'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00', 'after' => 'sgst_rate'];
        }
        if (!$this->db->field_exists('igst_rate', 'invoice_items')) {
            $item_fields['igst_rate'] = ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => '0.00', 'after' => 'sgst_amount'];
        }
        if (!$this->db->field_exists('igst_amount', 'invoice_items')) {
            $item_fields['igst_amount'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00', 'after' => 'igst_rate'];
        }
        if (!empty($item_fields)) {
            $this->dbforge->add_column('invoice_items', $item_fields);
            echo "Added GST columns to 'invoice_items' table.<br>";
        }

        echo "GST Database Setup Complete!";
    }
}
