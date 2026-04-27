<?php

namespace app\services;

use app\core\Session;

/** Сервис для управления авторизации и авторизационных данных */
readonly class AuthService {
    public function __construct(private Session $session) {}

    /**
     * Проверка существования пользователя
     *
     * @return bool
     */
    public function check(): bool {
        return !empty($this->session->has('user'));
    }

    /**
     * Получение данных пользователя
     *
     * @return array|null
     */
    public function user(): ?array {
        return $this->session->get('user');
    }

    /**
     * Добавление пользователя в сессию
     *
     * @param array $userData
     * @return void
     */
    public function login(array $userData): void {
        $this->session->set('user', $userData);
    }

    /**
     * Получение id пользователя
     *
     * @return int|null
     */
    public function id(): ?int {
        return $this->session->get('user')['id'] ?? null;
    }
}