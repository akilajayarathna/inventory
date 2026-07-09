<?php

class Category extends Model {

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM categories ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('
            INSERT INTO categories (name, description) 
            VALUES (:name, :description)
        ');
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('
            UPDATE categories 
            SET name = :name, description = :description 
            WHERE id = :id
        ');
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':id'          => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}