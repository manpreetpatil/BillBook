<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchases_model extends CI_Model
{
    public function get_all_purchases($user_id)
    {
        $this->db->select('purchases.*, suppliers.name as supplier_name');
        $this->db->join('suppliers', 'suppliers.id = purchases.supplier_id');
        $this->db->where('purchases.user_id', $user_id);
        $this->db->order_by('purchases.created_at', 'DESC');
        return $this->db->get('purchases')->result();
    }

    public function get_purchase($id, $user_id)
    {
        $this->db->select('purchases.*, suppliers.name as supplier_name, suppliers.email, suppliers.phone, suppliers.address, suppliers.gstin');
        $this->db->join('suppliers', 'suppliers.id = purchases.supplier_id');
        $this->db->where('purchases.id', $id);
        $this->db->where('purchases.user_id', $user_id);
        return $this->db->get('purchases')->row();
    }

    public function get_purchase_items($purchase_id)
    {
        $this->db->select('purchase_items.*, items.name as item_name');
        $this->db->join('items', 'items.id = purchase_items.item_id');
        $this->db->where('purchase_id', $purchase_id);
        return $this->db->get('purchase_items')->result();
    }

    public function create_purchase($purchase_data, $items_data, $user_id)
    {
        $this->db->trans_start();

        $purchase_data['user_id'] = $user_id;
        $this->db->insert('purchases', $purchase_data);
        $purchase_id = $this->db->insert_id();

        foreach ($items_data as $item) {
            $item['purchase_id'] = $purchase_id;
            $this->db->insert('purchase_items', $item);
            $purchase_item_id = $this->db->insert_id();

            // If status is Received, update stock
            if ($purchase_data['status'] == 'Received') {
                $this->_add_stock($item['item_id'], $item['quantity'], $item['batch_number'], $item['expiry_date'], $purchase_item_id, $user_id, $purchase_id);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status() ? $purchase_id : false;
    }

    private function _add_stock($item_id, $quantity, $batch_number, $expiry_date, $purchase_item_id, $user_id, $purchase_id)
    {
        // 1. Add to item_batches
        $batch_data = [
            'item_id' => $item_id,
            'batch_number' => $batch_number,
            'expiry_date' => !empty($expiry_date) ? $expiry_date : NULL,
            'quantity' => $quantity,
            'purchase_item_id' => $purchase_item_id
        ];
        $this->db->insert('item_batches', $batch_data);

        // 2. Update items.current_stock
        $this->db->set('current_stock', 'current_stock + ' . (float) $quantity, FALSE);
        $this->db->where('id', $item_id);
        $this->db->update('items');

        // 3. Log to inventory_logs
        $log_data = [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'type' => 'IN',
            'quantity' => $quantity,
            'reference_type' => 'Purchase',
            'reference_id' => $purchase_id,
            'date' => date('Y-m-d'),
            'notes' => "Batch: $batch_number"
        ];
        $this->db->insert('inventory_logs', $log_data);
    }
}
