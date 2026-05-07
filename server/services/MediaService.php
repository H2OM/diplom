<?php

namespace app\services;

use app\repositories\MediaRepository;

/** Сервис для управления медиа-информацией */
class MediaService {
    public function __construct(private readonly MediaRepository $mediaRepository) {}

    /**
     * Получение данных из таблицы 'новости'
     *
     * @return array
     */
    public function getNews(): array {
        return $this->mediaRepository->getNews();
    }
}