<?php

namespace app\services;

use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\core\Session;
use app\repositories\GoodsRepository;

/** Сервис для управления корзиной покупок */
class BasketService {
    private array $basket;

    public function __construct(private readonly Session $session, private readonly GoodsRepository $goodsRepository) {
        $basket = $this->session->get('basket');

        if(!$basket) {
            $this->session->set('basket', []);

            $basket = [];
        }

        $this->basket = $basket;
    }

    /**
     * Получение корзины покупок пользователя
     *
     * @return array
     */
    public function get(): array {
        return $this->basket;
    }

    /**
     * Сохранение корзины покупок в сессию
     *
     * @return array
     */
    public function save(): array {
        $this->basket = array_values($this->basket);
        $this->session->set('basket', $this->basket);

        return $this->basket;
    }

    /**
     * Добавление товара в корзину покупок
     *
     * @param string $id
     * @param string $size
     * @param int|null $userId
     * @param int $count
     * @return array
     * @throws ResponseException
     */
    public function add(string $id, string $size, ?int $userId, int $count = 1): array {
        $count = max(1, $count);
        $index = $this->findProductIndex(id: $id, size: $size);

        if($index !== false) {
            $this->basket[$index]['value'] += $count;

            return $this->save();
        }

        $product = $this->goodsRepository->getProductByIdAndSize($id, $size, $userId);

        if(!$product || count($product) === 0) {
            throw new ResponseException(responseMessage: ResponseMessage::ERROR_PRODUCT_NOT_FOUND, code: 404);
        }

        $product['size'] = $size;
        $product['value'] = $count;

        $this->basket[] = $product;

        return $this->save();
    }

    /**
     * Установка количества товара в корзине
     *
     * @param string $id
     * @param string $size
     * @param int $count
     * @return array
     * @throws ResponseException
     */
    public function setCount(string $id, string $size, int $count): array {
        $count = max(1, $count);
        $index = $this->findProductIndex(id: $id, size: $size);

        if($index === false) {
            throw new ResponseException(responseMessage: ResponseMessage::ERROR_PRODUCT_NOT_FOUND, code: 404);
        }

        $this->basket[$index]['value'] = $count;

        return $this->save();
    }

    /**
     * Уменьшить кол-во товара в корзине
     *
     * @param string $id
     * @param string $size
     * @return array
     * @throws ResponseException
     */
    public function decrement(string $id, string $size): array {
        $index = $this->findProductIndex(id: $id, size: $size);

        if($index === false) {
            throw new ResponseException(responseMessage: ResponseMessage::ERROR_PRODUCT_NOT_FOUND, code: 404);
        }

        if($this->basket[$index]['value'] === 1) {
            unset($this->basket[$index]);

        } else {
            $this->basket[$index]['value'] -= 1;
        }

        return $this->save();
    }

    /**
     * Удалить товар из корзины
     *
     * @param string $id
     * @param string $size
     * @return array
     * @throws ResponseException
     */
    public function remove(string $id, string $size): array {
        $index = $this->findProductIndex(id: $id, size: $size);

        if($index === false) {
            throw new ResponseException(responseMessage: ResponseMessage::ERROR_PRODUCT_NOT_FOUND, code: 404);
        }

        unset($this->basket[$index]);

        return $this->save();
    }

    /**
     * Отчистка
     *
     * @return array
     */
    public function clear(): array {
        $this->basket = [];

        return $this->save();
    }

    private function findProductIndex(string $id, string $size): int|false {
        foreach ($this->basket as $index => $product) {
            if ($product['id'] === $id && $product['size'] === $size) {
                return $index;
            }
        }

        return false;
    }
}