<?php

namespace app\services;

/** Сервис для авторизации и авторизационных данных */
class AuthService {
    /**
     * Проверка существования пользователя
     *
     * @return bool
     */
    public function check(): bool {
        return !empty($_SESSION['user']);
    }

    /**
     * Получение данных пользователя
     *
     * @return array|null
     */
    public function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Получение id пользователя
     *
     * @return int|null
     */
    public function id(): ?int {
        return $_SESSION['user']['id'] ?? null;
    }
}