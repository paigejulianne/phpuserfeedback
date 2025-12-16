<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($username, $email, $password) {
        $query = "INSERT INTO " . $this->table . " 
                  (username, email, password_hash) 
                  VALUES (:username, :email, :password_hash)";

        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $email = htmlspecialchars(strip_tags($email));
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $password_hash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $username, $email) {
        $query = "UPDATE " . $this->table . " SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $username = htmlspecialchars(strip_tags($username));
        $email = htmlspecialchars(strip_tags($email));

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function updatePassword($id, $password_hash) {
        $query = "UPDATE " . $this->table . " SET password_hash = :password_hash WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
