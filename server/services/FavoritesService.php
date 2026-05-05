<?php

namespace app\services;

use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\repositories\FavoritesRepository;

/** Сервис для управления избранным */
readonly class FavoritesService {
    public function __construct(private FavoritesRepository $favoritesRepository) {}

    /**
     * Получение
     *
     * @param int $userId
     * @return array
     */
    public function get(int $userId): array {
        return $this->favoritesRepository->get($userId);
    }

    /**
     * Добавление
     *
     * @param int $userId
     * @param string $productId
     * @return bool
     * @throws ResponseException
     */
    public function set(int $userId, string $productId): bool {
        $result = $this->favoritesRepository->set($userId, $productId);

        if(!$result) {
            throw new ResponseException(ResponseMessage::ERROR_UPDATE);
        }

        return true;
    }

    /**
     * Удаление
     *
     * @param int $userId
     * @param string $productId
     * @return bool
     * @throws ResponseException
     */
    public function remove(int $userId, string $productId): bool {
        $result = $this->favoritesRepository->remove($userId, $productId);

        if(!$result) {
            throw new ResponseException(ResponseMessage::ERROR_UPDATE);
        }

        return true;
    }
}