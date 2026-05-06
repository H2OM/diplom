<?php

namespace app\controllers;

use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\core\Request;
use app\core\Response;
use app\services\AuthService;
use app\services\BasketService;

/** Управление корзиной */
readonly class BasketController {
    public function __construct(
        private BasketService $basketService,
        private Request $session,
        private AuthService $authService
    ) {}

    /**
     * Получение корзины покупок пользователя
     *
     * @return Response
     */
    protected function getAction(): Response {
        return Response::jsonSuccess(data: $this->basketService->get());
    }

    /**
     * Добавление товара
     *
     * @return Response
     */
    protected function addAction(): Response {
        $id    = $this->session->post('id');
        $size  = $this->session->post('size');
        $count = (int)$this->session->post('count');

        if(!$id || !$size) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_ENOUGH_DATA, status: 403);
        }

        if($count <= 0) {
            $count = 1;
        }

        try {
            $basket = $this->basketService->add(
                id: $id,
                size: $size,
                userId: $this->authService->id(),
                count: $count
            );

            return Response::jsonSuccess(data: $basket);
        } catch (ResponseException $exception) {
            return Response::jsonError(message: $exception->getResponseMessage(), status: $exception->getCode() ?: 400);
        }
    }

    /**
     * Уменьшение кол-во товара
     *
     * @return Response
     */
    protected function decrementAction(): Response {
        $id   = $this->session->post('id');
        $size = $this->session->post('size');

        if(!$id || !$size) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_ENOUGH_DATA, status: 403);
        }

        try {
            $basket = $this->basketService->decrement(id: $id, size: $size);

            return Response::jsonSuccess(data: $basket);
        } catch (ResponseException $exception) {
            return Response::jsonError(message: $exception->getResponseMessage(), status: $exception->getCode() ?: 400);
        }
    }

    /**
     * Удаление товара
     *
     * @return Response
     */
    protected function removeAction(): Response {
        $id   = $this->session->post('id');
        $size = $this->session->post('size');

        try {
            $basket = $this->basketService->remove(id: $id, size: $size);

            return Response::jsonSuccess(data: $basket);
        } catch (ResponseException $exception) {
            return Response::jsonError(message: $exception->getResponseMessage(), status: $exception->getCode() ?: 400);
        }
    }

    /**
     * Установка кол-во товара
     *
     * @return Response
     */
    protected function setCountAction(): Response {
        $id = $this->session->post('id');
        $size    = $this->session->post('size');
        $count   = (int)$this->session->post('count');

        if(!$id || !$size || $count <= 0) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_ENOUGH_DATA, status: 403);
        }

        try {
            $basket = $this->basketService->setCount(id: $id, size: $size, count: $count);

            return Response::jsonSuccess(data: $basket);
        } catch (ResponseException $exception) {
            return Response::jsonError(message: $exception->getResponseMessage(), status: $exception->getCode() ?: 400);
        }
    }

    /**
     * Отчистка корзины
     *
     * @return Response
     */
    protected function clearAction(): Response {
        return Response::jsonSuccess(data: $this->basketService->clear());
    }
}