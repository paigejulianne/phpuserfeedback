<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class PasswordReset {
    private $conn;
    private $table = 'password_resets';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createToken($email) {
        // Delete any existing tokens for this email
        $this->deleteByEmail($email);

        $token = bin2hex(random_bytes(32));
        
        $query = "INSERT INTO " . $this->table . " (email, token) VALUES (:email, :token)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }

    public function findByToken($token) {
        $query = "SELECT * FROM " . $this->table . " WHERE token = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if expired (e.g., 1 hour)
        if ($record) {
            $created = strtotime($record['created_at']);
            if (time() - $created > 3600) {
                return false; // Expired
            }
        }
        
        return $record;
    }

    public function deleteByEmail($email) {
        $query = "DELETE FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
}
