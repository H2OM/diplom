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
        private readonly AuthService         $authService,
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
     * @return array
     */
    public function get(): array {
        if(!$this->authService->id()) return $this->favorites;

        $this->favorites = $this->favoritesRepository->get($this->authService->id());

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
     * @return array
     * @throws ResponseException
     */
    public function add(int $productId): array {
        if(in_array($productId, $this->favorites)) {
            throw new ResponseException(ResponseMessage::ERROR_DUPLICATE);
        }

        if(!$this->goodsRepository->getProductId($productId)) {
            throw new ResponseException(ResponseMessage::ERROR_PRODUCT_NOT_FOUND);
        }

        if($this->authService->id() && !$this->favoritesRepository->add($this->authService->id(), $productId)) {
            throw new ResponseException(ResponseMessage::ERROR_ADD);
        }

        $this->favorites[] = $productId;

        return $this->save();
    }

    /**
     * Удаление
     *
     * @param string $productId
     * @return array
     * @throws ResponseException
     */
    public function remove(string $productId): array {
        if($this->authService->id() && !$this->favoritesRepository->remove($this->authService->id(), $productId)) {
            throw new ResponseException(ResponseMessage::ERROR_UPDATE);
        }

        unset($this->favorites[array_search($productId, $this->favorites)]);

        return $this->save();
    }
}