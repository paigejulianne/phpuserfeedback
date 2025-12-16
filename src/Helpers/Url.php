<?php

namespace App\Helpers;

class Url {
    public static function base() {
        // Returns /phpuserfeedback/public (no trailing slash)
        return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public static function to($path = '') {
        $path = ltrim($path, '/');
        if (empty($path)) {
            return self::base();
        }
        return self::base() . '/' . $path;
    }

    public static function redirect($path = '') {
        header('Location: ' . self::to($path));
        exit;
    }
}
