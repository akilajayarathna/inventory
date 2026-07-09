<?php

class Dashboard extends Model {

    public function getTotalProducts() {
        $stmt = $this->db->query('SELECT COUNT(*) as total FROM products WHERE deleted_at IS NULL');
        return $stmt->fetch()->total;
    }

    public function getTotalCategories() {
        $stmt = $this->db->query('SELECT COUNT(*) as total FROM categories');
        return $stmt->fetch()->total;
    }

    public function getTotalSuppliers() {
        $stmt = $this->db->query('SELECT COUNT(*) as total FROM suppliers WHERE deleted_at IS NULL');
        return $stmt->fetch()->total;
    }

    public function getLowStockProducts() {
        $stmt = $this->db->query('
            SELECT p.name, p.reorder_level,
                COALESCE(SUM(sm.quantity), 0) as current_stock
            FROM products p
            LEFT JOIN stock_movements sm ON p.id = sm.product_id
            WHERE p.deleted_at IS NULL
            GROUP BY p.id, p.name, p.reorder_level
            HAVING current_stock <= p.reorder_level
        ');
        return $stmt->fetchAll();
    }
}