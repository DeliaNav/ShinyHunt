<?php
class Auth {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn() {
        self::init();
        return isset($_SESSION['user_id']);
    }

    public static function userId() {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }
}
