<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class ApiToken {
    private $conn;
    private $table = 'api_tokens';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function generate($user_id) {
        $token = bin2hex(random_bytes(32));
        
        $query = "INSERT INTO " . $this->table . " (user_id, token) VALUES (:user_id, :token)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':token', $token);
        
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }

    public function validate($token) {
        $query = "SELECT user_id FROM " . $this->table . " WHERE token = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['user_id'] : false;
    }
}
