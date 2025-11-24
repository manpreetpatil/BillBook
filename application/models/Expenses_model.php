<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses_model extends CI_Model
{
    // --- Categories ---

    public function get_categories($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('name', 'ASC');
        return $this->db->get('expense_categories')->result();
    }

    public function create_category($data)
    {
        return $this->db->insert('expense_categories', $data);
    }

    public function delete_category($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('expense_categories');
    }

    // --- Expenses ---

    public function get_all_expenses($user_id, $filters = [])
    {
        $this->db->select('expenses.*, expense_categories.name as category_name');
        $this->db->join('expense_categories', 'expense_categories.id = expenses.category_id');
        $this->db->where('expenses.user_id', $user_id);

        if (!empty($filters['start_date'])) {
            $this->db->where('expenses.expense_date >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('expenses.expense_date <=', $filters['end_date']);
        }
        if (!empty($filters['category_id'])) {
            $this->db->where('expenses.category_id', $filters['category_id']);
        }

        $this->db->order_by('expenses.expense_date', 'DESC');
        return $this->db->get('expenses')->result();
    }

    public function get_expense($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->get('expenses')->row();
    }

    public function create_expense($data)
    {
        return $this->db->insert('expenses', $data);
    }

    public function update_expense($id, $data, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('expenses', $data);
    }

    public function delete_expense($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('expenses');
    }

    // --- Reporting ---

    public function get_total_expenses($user_id, $start_date = null, $end_date = null)
    {
        $this->db->select_sum('amount');
        $this->db->where('user_id', $user_id);
        if ($start_date)
            $this->db->where('expense_date >=', $start_date);
        if ($end_date)
            $this->db->where('expense_date <=', $end_date);
        $query = $this->db->get('expenses');
        return $query->row()->amount ?? 0;
    }
}
