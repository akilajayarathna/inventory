<?php

class Sale extends Model {

    public function create($userId, $totalAmount, $paymentMethod) {
        $stmt = $this->db->prepare('
            INSERT INTO sales (user_id, total_amount, payment_method) 
            VALUES (:user_id, :total_amount, :payment_method)
        ');
        $stmt->execute([
            ':user_id'        => $userId,
            ':total_amount'   => $totalAmount,
            ':payment_method' => $paymentMethod
        ]);
        return $this->db->lastInsertId();
    }

    public function addItem($saleId, $productId, $quantity, $unitPrice) {
        $stmt = $this->db->prepare('
            INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) 
            VALUES (:sale_id, :product_id, :quantity, :unit_price)
        ');
        return $stmt->execute([
            ':sale_id'    => $saleId,
            ':product_id' => $productId,
            ':quantity'   => $quantity,
            ':unit_price' => $unitPrice
        ]);
    }
}