<?php

class PurchaseOrder extends Model {

    public function getAll() {
        $stmt = $this->db->query('
            SELECT po.*, s.name as supplier_name, u.name as user_name
            FROM purchase_orders po
            LEFT JOIN suppliers s ON po.supplier_id = s.id
            LEFT JOIN users u ON po.user_id = u.id
            ORDER BY po.ordered_at DESC
        ');
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare('
            SELECT po.*, s.name as supplier_name, u.name as user_name
            FROM purchase_orders po
            LEFT JOIN suppliers s ON po.supplier_id = s.id
            LEFT JOIN users u ON po.user_id = u.id
            WHERE po.id = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('
            INSERT INTO purchase_orders (supplier_id, user_id, status, total_amount) 
            VALUES (:supplier_id, :user_id, :status, :total_amount)
        ');
        $stmt->execute([
            ':supplier_id'  => $data['supplier_id'],
            ':user_id'      => $data['user_id'],
            ':status'       => 'pending',
            ':total_amount' => $data['total_amount']
        ]);
        return $this->db->lastInsertId();
    }

    public function markAsReceived($id) {
        $stmt = $this->db->prepare('
            UPDATE purchase_orders 
            SET status = :status, received_at = NOW() 
            WHERE id = :id
        ');
        return $stmt->execute([':status' => 'received', ':id' => $id]);
    }

    public function cancel($id) {
        $stmt = $this->db->prepare('UPDATE purchase_orders SET status = :status WHERE id = :id');
        return $stmt->execute([':status' => 'cancelled', ':id' => $id]);
    }
}