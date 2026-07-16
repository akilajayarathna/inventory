<?php

class User extends Model {

    public function findByEmail($email) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

// -------------------------------------------------------------

    public function createToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));

        $stmt = $this->db->prepare('
            INSERT INTO api_tokens (user_id, token, expires_at) 
            VALUES (:user_id, :token, :expires_at)
        ');
        $stmt->execute([
            ':user_id'    => $userId,
            ':token'      => $token,
            ':expires_at' => $expiresAt
        ]);

        return $token;
    }

    public function findByToken($token) {
        $stmt = $this->db->prepare('
            SELECT u.* FROM users u
            INNER JOIN api_tokens t ON u.id = t.user_id
            WHERE t.token = :token AND t.expires_at > NOW()
        ');
        $stmt->execute([':token' => $token]);
        return $stmt->fetch();
    }
}