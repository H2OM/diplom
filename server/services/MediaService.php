<?php

namespace app\services;

use app\core\Db;

/** Сервис для работы с медиа-информацией */
readonly class MediaService {
    public function __construct(private Db $db) {}

    /**
     * Получение данных из таблицы 'новости'
     *
     * @return array
     */
    public function getMainNews(): array {
        return $this->db->query()->table('news')->get();
    }
}