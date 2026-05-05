<?php

namespace app\controllers;

use app\core\enums\ResponseMessage;
use app\core\exceptions\ResponseException;
use app\core\Request;
use app\core\Response;
use app\services\AuthService;
use app\services\UserService;
use Exception;

/** Контролер для управления пользователями */
readonly class UserController {
    public function __construct(
        private UserService $userService,
        private AuthService $authService,
        private Request     $request
    ) {}

    /**
     * Проверка авторизации
     *
     * @return Response
     */
    public function isAuthAction(): Response {
        return Response::jsonSuccess(data: ['authorized' => $this->authService->check()]);
    }

    /**
     * Авторизация
     *
     * @return Response
     */
    protected function signInAction(): Response {
        if($this->authService->check()) {
            return Response::jsonSuccess(message: ResponseMessage::USER_ALREADY);
        }

        try {
            $user = $this->userService->signIn(
                password: $this->request->post('password'),
                phone: $this->request->post('phone')
            );

            $this->authService->login(userData: $user);

            return Response::jsonSuccess(data: $this->authService->user(), message: ResponseMessage::SUCCESS_AUTH);
        } catch (ResponseException $e) {
            return Response::jsonError(message: $e->getResponseMessage(), status: $e->getCode() ?: 400);

        } catch (Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Регистрация
     *
     * @return Response
     * @throws Exception
     */
    protected function signUpAction(): Response {
        if($this->authService->check()) {
            return Response::jsonSuccess(message: ResponseMessage::USER_ALREADY);
        }

        try {
            $user = $this->userService->signUp($this->request->post());

            $this->authService->login(userData: $user);

            return Response::jsonSuccess(data: $this->authService->user(), message: ResponseMessage::SUCCESS_AUTH);
        } catch (ResponseException $e) {
            return Response::jsonError(message: $e->getResponseMessage(), status: $e->getCode() ?: 400);

        } catch (Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Деавторизация
     *
     * @return Response
     */
    protected function logOutAction(): Response {
        if(!$this->authService->check()) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_AUTH, status: 401);
        }

        $this->authService->logout();

        return Response::jsonSuccess(message: ResponseMessage::SUCCESS_LOGOUT);
    }

    /**
     * Получение
     *
     * @return Response
     */
    protected function getAction(): Response {
        if(!$this->authService->check()) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_AUTH, status: 401);
        }

        return Response::jsonSuccess(data: $this->authService->user());
    }

    /**
     * Редактирование
     *
     * @return Response
     */
    protected function editAction(): Response {
        if(!$this->authService->check()) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_AUTH, status: 401);
        }

        try {
            $updatedUser = $this->userService->edit(
                userData: $this->request->post(),
                userId: $this->authService->id()
            );

            $this->authService->login(userData: $updatedUser + $this->authService->user());

            return Response::jsonSuccess(data: $this->authService->user());
        } catch (ResponseException $e) {
            return Response::jsonError(message: $e->getResponseMessage(), status: $e->getCode() ?: 400);

        } catch (\Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Получение всех заказов
     *
     * @return Response
     */
    protected function getOrdersAction(): Response {
        if(!$this->authService->check()) {
            return Response::jsonError(message: ResponseMessage::ERROR_NOT_AUTH, status: 401);
        }

        $orders = $this->userService->getOrders(userId: $this->authService->id());

        return Response::jsonSuccess(data: $orders);
    }
}
