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
            if(!$this->userService->signIn(
                password: $this->request->post('password'),
                phone: $this->request->post('phone')
            )) {
                throw new \Exception('Непредвиденная ошибка', 500);
            }

            return Response::json(data: ['success' => true, 'message' => 'Авторизация прошла успешно']);
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

            $this->authService->login($userData);

            return Response::json(data: ['success' => true, 'message' => 'Регистрация прошла успешно']);
        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }
}