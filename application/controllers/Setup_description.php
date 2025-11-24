<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_description extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        $this->load->dbforge();

        $fields = array(
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'item_name'
            )
        );

        if ($this->dbforge->add_column('invoice_items', $fields)) {
            echo "Column 'description' added to 'invoice_items' table successfully.<br>";
        } else {
            echo "Failed to add column 'description' or it already exists.<br>";
        }

        echo "Setup Complete.";
    }
}
