<?php

namespace app\services;

use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\core\Session;
use app\repositories\FavoritesRepository;
use app\repositories\GoodsRepository;

/** Сервис для управления избранным */
class FavoritesService {
    private array $favorites;

    public function __construct(
        private readonly FavoritesRepository $favoritesRepository,
        private readonly GoodsRepository     $goodsRepository,
        private readonly Session             $session
    ) {
        $favorites = $this->session->get('favorites');

        if(!$favorites) {
            $this->session->set('favorites', []);

            $favorites = [];
        }

        $this->favorites = $favorites;
    }

    /**
     * Получение
     *
     * @param int|null $userId
     * @return array
     */
    public function get(?int $userId): array {
        if(!$userId) return $this->favorites;

        $this->favorites = $this->favoritesRepository->get($userId);

        return $this->save();
    }

    /**
     * Сохранение в сессию
     *
     * @return array
     */
    public function save(): array {
        $this->favorites = array_values($this->favorites);
        $this->session->set('favorites', $this->favorites);

        return $this->favorites;
    }

    /**
     * Добавление
     *
     * @param int $productId
     * @param int|null $userId
     * @return array
     * @throws ResponseException
     */
    public function add(int $productId, ?int $userId): array {
        if(in_array($productId, $this->favorites)) {
            throw new ResponseException(ResponseMessage::ERROR_DUPLICATE);
        }

        if(!$this->goodsRepository->getProductById($productId)) {
            throw new ResponseException(ResponseMessage::ERROR_PRODUCT_NOT_FOUND);
        }

        if($userId && !$this->favoritesRepository->add($userId, $productId)) {
            throw new ResponseException(ResponseMessage::ERROR_ADD);
        }

        $this->favorites[] = $productId;

        return $this->save();
    }

    /**
     * Удаление
     *
     * @param string $productId
     * @param int|null $userId
     * @return array
     * @throws ResponseException
     */
    public function remove(string $productId, ?int $userId): array {
        if($userId && !$this->favoritesRepository->remove($userId, $productId)) {
            throw new ResponseException(ResponseMessage::ERROR_UPDATE);
        }

        unset($this->favorites[array_search($productId, $this->favorites)]);

        return $this->save();
    }
}