<?php

namespace app\controllers;

use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\services\AuthService;
use app\services\FavoritesService;
use app\core\Request;
use app\core\Response;

/** Контроллер для управления избранным */
readonly class FavoritesController {
    public function __construct(
        private FavoritesService $favoritesService,
        private AuthService $authService,
        private Request     $request
    ) {}

    /**
     * Получение
     *
     * @return Response
     */
    protected function getAction(): Response {
        if(!$this->authService->check()) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_AUTH, status: 401);
        }

        $favorites = $this->favoritesService->get(userId: $this->authService->id());

        return Response::jsonSuccess(data: $favorites);
    }


    /**
     * Добавление
     *
     * @return Response
     */
    protected function addAction(): Response {
        if(!$this->authService->check()) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_AUTH, status: 401);
        }

        $productId = $this->request->get('product_id');

        if(empty($productId)) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_ENOUGH_DATA, status: 403);
        }

        try {
            $this->favoritesService->add(userId: $this->authService->id(), productId: $productId);

            return Response::jsonSuccess(message: ResponseMessage::SUCCESS_ADD);
        } catch (ResponseException $e) {
            return Response::jsonError(message: $e->getResponseMessage(), status: $e->getCode() ?: 400);
        }
    }

    /**
     * Удаление
     *
     * @return Response
     */
    protected function removeAction(): Response {
        if(!$this->authService->check()) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_AUTH, status: 401);
        }

        $productId = $this->request->get('product_id');

        if(empty($productId)) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_ENOUGH_DATA, status: 403);
        }

        try {
            $this->favoritesService->remove(userId: $this->authService->id(), productId: $productId);

            return Response::jsonSuccess(message: ResponseMessage::SUCCESS_REMOVE);
        } catch (ResponseException $e) {
            return Response::jsonError(message: $e->getResponseMessage(), status: $e->getCode() ?: 400);
        }
    }
}