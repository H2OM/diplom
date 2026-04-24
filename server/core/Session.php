<?php

namespace app\core;

/** Управление сессией */
class Session {
    /**
     * Уничтожение сессии
     *
     * @return void
     */
    public static function destroySession(): void {
        $_SESSION = [];

        if (session_id() != "" || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 2592000, '/');
        }

        session_destroy();
    }
}