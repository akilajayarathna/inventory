<?php

class Product extends Model {

    public function getAll() {
        $stmt = $this->db->query('
            SELECT p.*, c.name as category_name, s.name as supplier_name,
                COALESCE(SUM(sm.quantity), 0) as current_stock
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            LEFT JOIN stock_movements sm ON p.id = sm.product_id
            WHERE p.deleted_at IS NULL
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare('
            SELECT p.*, 
                COALESCE(SUM(sm.quantity), 0) as current_stock
            FROM products p
            LEFT JOIN stock_movements sm ON p.id = sm.product_id
            WHERE p.id = :id AND p.deleted_at IS NULL
            GROUP BY p.id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('
            INSERT INTO products (category_id, supplier_id, name, sku, description, unit_price, reorder_level) 
            VALUES (:category_id, :supplier_id, :name, :sku, :description, :unit_price, :reorder_level)
        ');
        return $stmt->execute([
            ':category_id'   => $data['category_id'],
            ':supplier_id'   => $data['supplier_id'],
            ':name'          => $data['name'],
            ':sku'           => $data['sku'],
            ':description'   => $data['description'],
            ':unit_price'    => $data['unit_price'],
            ':reorder_level' => $data['reorder_level']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('
            UPDATE products 
            SET category_id = :category_id, supplier_id = :supplier_id, name = :name, 
                sku = :sku, description = :description, unit_price = :unit_price, 
                reorder_level = :reorder_level 
            WHERE id = :id
        ');
        return $stmt->execute([
            ':category_id'   => $data['category_id'],
            ':supplier_id'   => $data['supplier_id'],
            ':name'          => $data['name'],
            ':sku'           => $data['sku'],
            ':description'   => $data['description'],
            ':unit_price'    => $data['unit_price'],
            ':reorder_level' => $data['reorder_level'],
            ':id'            => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('UPDATE products SET deleted_at = NOW() WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function skuExists($sku, $excludeId = null) {
        if($excludeId) {
            $stmt = $this->db->prepare('SELECT id FROM products WHERE sku = :sku AND id != :id AND deleted_at IS NULL');
            $stmt->execute([':sku' => $sku, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT id FROM products WHERE sku = :sku AND deleted_at IS NULL');
            $stmt->execute([':sku' => $sku]);
        }
        return $stmt->fetch() !== false;
    }
}