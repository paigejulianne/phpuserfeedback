<?php

namespace App\Helpers;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
        self::start();
        session_destroy();
    }

    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        self::start();
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role'] ?? 'guest'
        ];
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ../public/login');
            exit;
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (self::user()['role'] !== 'admin') {
            header('Location: ../public/');
            exit;
        }
    }
}
