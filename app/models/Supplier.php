<?php

class Supplier extends Model {

    public function getAll() {
        $stmt = $this->db->query('
            SELECT * FROM suppliers 
            WHERE deleted_at IS NULL 
            ORDER BY created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare('
            SELECT * FROM suppliers 
            WHERE id = :id AND deleted_at IS NULL
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('
            INSERT INTO suppliers (name, contact_person, phone, email, address) 
            VALUES (:name, :contact_person, :phone, :email, :address)
        ');
        return $stmt->execute([
            ':name'           => $data['name'],
            ':contact_person' => $data['contact_person'],
            ':phone'          => $data['phone'],
            ':email'          => $data['email'],
            ':address'        => $data['address']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('
            UPDATE suppliers 
            SET name = :name, contact_person = :contact_person, 
                phone = :phone, email = :email, address = :address 
            WHERE id = :id
        ');
        return $stmt->execute([
            ':name'           => $data['name'],
            ':contact_person' => $data['contact_person'],
            ':phone'          => $data['phone'],
            ':email'          => $data['email'],
            ':address'        => $data['address'],
            ':id'             => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('
            UPDATE suppliers 
            SET deleted_at = NOW() 
            WHERE id = :id
        ');
        return $stmt->execute([':id' => $id]);
    }
}