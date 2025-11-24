<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_sharing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function index()
    {
        // Add 'share_hash' to 'invoices' table
        $fields = array(
            'share_hash' => array(
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => TRUE,
                'unique' => TRUE,
                'after' => 'status'
            )
        );

        if (!$this->db->field_exists('share_hash', 'invoices')) {
            $this->dbforge->add_column('invoices', $fields);
            echo "Added share_hash column to invoices table.<br>";
        } else {
            echo "share_hash column already exists in invoices table.<br>";
        }

        echo "Database setup for sharing completed!";
    }
}
