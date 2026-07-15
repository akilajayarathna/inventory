<?php

class PurchaseOrderItem extends Model {

    public function create($data) {
        $stmt = $this->db->prepare('
            INSERT INTO purchase_order_items (purchase_order_id, product_id, quantity, unit_cost) 
            VALUES (:purchase_order_id, :product_id, :quantity, :unit_cost)
        ');
        return $stmt->execute([
            ':purchase_order_id' => $data['purchase_order_id'],
            ':product_id'        => $data['product_id'],
            ':quantity'          => $data['quantity'],
            ':unit_cost'         => $data['unit_cost']
        ]);
    }

    public function getByOrderId($orderId) {
        $stmt = $this->db->prepare('
            SELECT poi.*, p.name as product_name, p.sku
            FROM purchase_order_items poi
            LEFT JOIN products p ON poi.product_id = p.id
            WHERE poi.purchase_order_id = :order_id
        ');
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}