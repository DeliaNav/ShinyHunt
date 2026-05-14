<?php

class Auth {
    public static function check(): bool {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['user_id']);
    }

    public static function require(): void {
        if (!self::check()) {
            header("Location: /TFG/Codigo/login");
            exit();
        }
    }

    public static function userId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public static function username(): ?string {
        return $_SESSION['nombre'] ?? null;
    }

    public static function logout(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: /TFG/Codigo/");
        exit();
    }
}
