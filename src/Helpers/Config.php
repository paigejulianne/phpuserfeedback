<?php

namespace App\Helpers;

class Config {
    private static $config = null;

    public static function load() {
        if (self::$config === null) {
            $path = __DIR__ . '/../Config/config.php';
            if (file_exists($path)) {
                self::$config = require $path;
            } else {
                self::$config = [];
            }
        }
    }

    public static function get($key, $default = null) {
        self::load();
        return self::$config[$key] ?? $default;
    }
}
