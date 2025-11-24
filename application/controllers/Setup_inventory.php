<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_inventory extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        $this->load->dbforge();

        // 1. Create 'suppliers' table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'user_id' => array('type' => 'INT', 'constraint' => 11),
            'name' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'email' => array('type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE),
            'phone' => array('type' => 'VARCHAR', 'constraint' => '20', 'null' => TRUE),
            'address' => array('type' => 'TEXT', 'null' => TRUE),
            'gstin' => array('type' => 'VARCHAR', 'constraint' => '20', 'null' => TRUE),
            'created_at' => array('type' => 'TIMESTAMP', 'default_string' => 'CURRENT_TIMESTAMP')
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        if ($this->dbforge->create_table('suppliers', TRUE)) {
            echo "Table 'suppliers' created.<br>";
        }

        // 2. Create 'purchases' table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'user_id' => array('type' => 'INT', 'constraint' => 11),
            'supplier_id' => array('type' => 'INT', 'constraint' => 11),
            'purchase_date' => array('type' => 'DATE'),
            'status' => array('type' => 'ENUM("Ordered","Received","Cancelled")', 'default' => 'Ordered'),
            'total_amount' => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'),
            'notes' => array('type' => 'TEXT', 'null' => TRUE),
            'created_at' => array('type' => 'TIMESTAMP', 'default_string' => 'CURRENT_TIMESTAMP')
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('supplier_id');
        if ($this->dbforge->create_table('purchases', TRUE)) {
            echo "Table 'purchases' created.<br>";
        }

        // 3. Create 'purchase_items' table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'purchase_id' => array('type' => 'INT', 'constraint' => 11),
            'item_id' => array('type' => 'INT', 'constraint' => 11),
            'quantity' => array('type' => 'DECIMAL', 'constraint' => '10,2'),
            'unit_price' => array('type' => 'DECIMAL', 'constraint' => '10,2'),
            'expiry_date' => array('type' => 'DATE', 'null' => TRUE),
            'batch_number' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'total' => array('type' => 'DECIMAL', 'constraint' => '10,2')
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('purchase_id');
        $this->dbforge->add_key('item_id');
        if ($this->dbforge->create_table('purchase_items', TRUE)) {
            echo "Table 'purchase_items' created.<br>";
        }

        // 4. Create 'item_batches' table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'item_id' => array('type' => 'INT', 'constraint' => 11),
            'batch_number' => array('type' => 'VARCHAR', 'constraint' => '50'),
            'expiry_date' => array('type' => 'DATE', 'null' => TRUE),
            'quantity' => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'),
            'purchase_item_id' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'created_at' => array('type' => 'TIMESTAMP', 'default_string' => 'CURRENT_TIMESTAMP')
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('item_id');
        if ($this->dbforge->create_table('item_batches', TRUE)) {
            echo "Table 'item_batches' created.<br>";
        }

        // 5. Create 'inventory_logs' table
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'user_id' => array('type' => 'INT', 'constraint' => 11),
            'item_id' => array('type' => 'INT', 'constraint' => 11),
            'type' => array('type' => 'ENUM("IN","OUT","ADJUST")', 'default' => 'IN'),
            'quantity' => array('type' => 'DECIMAL', 'constraint' => '10,2'),
            'reference_type' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE), // Purchase, Invoice, Manual
            'reference_id' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'date' => array('type' => 'DATE'),
            'notes' => array('type' => 'TEXT', 'null' => TRUE),
            'created_at' => array('type' => 'TIMESTAMP', 'default_string' => 'CURRENT_TIMESTAMP')
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('item_id');
        if ($this->dbforge->create_table('inventory_logs', TRUE)) {
            echo "Table 'inventory_logs' created.<br>";
        }

        // 6. Update 'items' table
        $fields = array(
            'current_stock' => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00', 'after' => 'price'),
            'low_stock_alert' => array('type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '10.00', 'after' => 'current_stock')
        );

        // Check if columns exist before adding
        if (!$this->db->field_exists('current_stock', 'items')) {
            $this->dbforge->add_column('items', $fields);
            echo "Columns 'current_stock' and 'low_stock_alert' added to 'items'.<br>";
        } else {
            echo "Columns already exist in 'items'.<br>";
        }

        echo "Inventory Setup Complete.";
    }
}
