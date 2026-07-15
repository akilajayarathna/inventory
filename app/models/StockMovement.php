<?php

class StockMovement extends Model {

    public function getAll() {
        $stmt = $this->db->query('
            SELECT sm.*, p.name as product_name, p.sku, u.name as user_name
            FROM stock_movements sm
            LEFT JOIN products p ON sm.product_id = p.id
            LEFT JOIN users u ON sm.user_id = u.id
            ORDER BY sm.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public function getByProduct($productId) {
        $stmt = $this->db->prepare('
            SELECT sm.*, u.name as user_name
            FROM stock_movements sm
            LEFT JOIN users u ON sm.user_id = u.id
            WHERE sm.product_id = :product_id
            ORDER BY sm.created_at DESC
        ');
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare('
            INSERT INTO stock_movements (product_id, user_id, type, quantity, reference_id, note) 
            VALUES (:product_id, :user_id, :type, :quantity, :reference_id, :note)
        ');
        return $stmt->execute([
            ':product_id'   => $data['product_id'],
            ':user_id'      => $data['user_id'],
            ':type'         => $data['type'],
            ':quantity'     => $data['quantity'],
            ':reference_id' => $data['reference_id'] ?? null,
            ':note'         => $data['note'] ?? null
        ]);
    }

    public function getCurrentStock($productId) {
        $stmt = $this->db->prepare('
            SELECT COALESCE(SUM(quantity), 0) as stock 
            FROM stock_movements 
            WHERE product_id = :product_id
        ');
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetch()->stock;
    }
}