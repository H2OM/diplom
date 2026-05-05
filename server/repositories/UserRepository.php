<?php

namespace app\repositories;

use app\core\Db;

/** Репозиторий для управления пользователями */
readonly class UserRepository {
    public function __construct(private Db $db) {}

    /**
     * Получения пользователя по номеру телефона
     *
     * @param string $phone
     * @return array
     */
    public function getByPhone(string $phone): array {
        return $this->db->query()
            ->table('users')
            ->where('phone', $phone)
            ->get();
    }

    /**
     * Добавление нового пользователя
     *
     * @param array $userData
     * @return string|false
     */
    public function insert(array $userData): string|false {
        return $this->db->query()
            ->table('users')
            ->insert($userData);
    }

    /**
     * Редактирование пользователя
     *
     * @param int $userId
     * @param array $userData
     * @return string|false
     */
    public function edit(int $userId, array $userData): string|false {
        return $this->db->query()
            ->table('users')
            ->where('id', '=', $userId)
            ->update($userData);
    }
}