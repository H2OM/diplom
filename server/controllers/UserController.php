<?php

namespace app\controllers;

use app\core\Request;
use app\core\Response;
use app\services\AuthService;
use app\services\UserService;

/** Контролер для управления пользователями */
readonly class UserController {
    public function __construct(
        private UserService $userService,
        private AuthService $authService,
        private Request     $request
    ) {}

    /**
     * Проверка авторизации пользователя
     *
     * @return Response
     */
    public function isAuthAction(): Response {
        return Response::json(data: $this->authService->check());
    }

    /**
     * Авторизация пользователя
     *
     * @return Response
     */
    protected function signInAction(): Response {
        if($this->authService->check()) {
            return Response::json(data: ['success' => true, 'message' => 'Пользователь уже авторизован']);
        }

        try {
            $user = $this->userService->signIn(
                password: $this->request->post('password'),
                phone: $this->request->post('phone')
            );

            $this->authService->login($user);

            return Response::json(data: ['success' => true, 'data' => $this->authService->user()]);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Регистрация пользователя
     *
     * @return Response
     */
    protected function signUpAction(): Response {
        if($this->authService->check()) {
            return Response::json(data: ['success' => true, 'message' => 'Пользователь уже авторизован']);
        }

        try {
            $userData = $this->userService->signUp($this->request->post());

            $this->authService->login(userData: $userData);

            return Response::json(data: ['success' => true, 'data' => $this->authService->user()]);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Деавторизация пользователя
     *
     * @return Response
     */
    protected function logOutAction(): Response {
        if(!$this->authService->check()) {
            return Response::json(data: ['error' => true, 'message' => 'Не авторизирован'], status: 401);
        }

        $this->authService->logout();

        return Response::json(data: ['success' => true, 'message' => 'Успешная деавторизация']);
    }

    /**
     * Получение данных пользователя
     *
     * @return Response
     */
    protected function getUserAction(): Response {
        if(!$this->authService->check()) {
            return Response::json(data: ['error' => true, 'message' => 'Не авторизирован'], status: 401);
        }

        return Response::json(data: ['success' => true, 'data' => $this->authService->user()]);
    }

    /**
     * Редактирование данных пользователя
     *
     * @return Response
     */
    protected function editUserAction(): Response {
        if(!$this->authService->check()) {
            return Response::json(data: ['error' => true, 'message' => 'Не авторизирован'], status: 401);
        }

        try {
            $updateUser = $this->userService->editUser(userData: $this->request->post(), userId: $this->authService->id());

            $this->authService->login(userData: $updateUser + $this->authService->user());

            return Response::json(data: ['success' => true, 'data' => $this->authService->user()]);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Получение избранных товаров пользователя
     *
     * @return Response
     */
    protected function getFavoritesAction(): Response {
        if(!$this->authService->check()) {
            return Response::json(data: ['error' => true, 'message' => 'Не авторизирован'], status: 401);
        }

        try {
            $favorites = $this->userService->getFavorites(userId: $this->authService->id());

            return Response::json(data: ['success' => true, 'data' => $favorites]);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Изменение 'избранного' пользователя
     *
     * @return Response
     */
    protected function changeFavoriteAction(): Response {
        if(!$this->authService->check()) {
            return Response::json(data: ['error' => true, 'message' => 'Не авторизирован'], status: 401);
        }

        $productId = $this->request->get('product_id');
        $action = $this->request->get('action');

        if(empty($productId) || empty($action)) {
            return Response::json(data: ['error' => true, 'message' => 'Не достаточно данных'], status: 403);
        }

        try {
            $result = $this->userService->changeFavorites(userId: $this->authService->id(), productId: $productId, action: $action);

            if(!$result) {
                throw new \Exception('Ошибка при изменении данных', 500);
            }

            return Response::json(data: ['success' => true, 'message' => 'Успешно']);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Получение всех заказов пользователя
     *
     * @return Response
     */
    protected function getOrdersAction(): Response {
        if(!$this->authService->check()) {
            return Response::json(data: ['error' => true, 'message' => 'Не авторизирован'], status: 401);
        }

        try {
            $orders = $this->userService->getUserOrders(userId: $this->authService->id());

            return Response::json(data: ['success' => true, 'data' => $orders]);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }
}
