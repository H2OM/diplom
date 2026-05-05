<?php

namespace app\controllers;

use app\core\enums\ResponseMessage;
use app\core\Request;
use app\core\Response;
use app\services\CallbackService;
use Exception;

/** Контролер для управления обратной связи */
readonly class CallbackController {
    public function __construct(
        private Request $request,
        private CallbackService $callbackService) {}

    /**
     * Обработка формы подписки на новости
     *
     * @return Response
     */
    protected function subscribeAction(): Response {
        $email = $this->request->post('email');

        if(!$email) {
            return Response::jsonError(message: ResponseMessage::ERROR_DATA, status: 403);
        }

        try {
            $this->callbackService->subscribe(email: $email);

            return Response::jsonSuccess(message: ResponseMessage::SUCCESS_SUBSCRIBE);
        } catch (Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }

    /**
     * Обработка формы обратной связи
     *
     * @return Response
     */
    protected function formAction(): Response {
        try {
            $this->callbackService->form(data: $this->request->post());

            return Response::jsonSuccess(message: ResponseMessage::SUCCESS_FORM);
        } catch (Exception $e) {
            return Response::json(data: [
                'error' => true,
                'message' => $e->getMessage()
            ], status: $e->getCode() ?: 400);
        }
    }
}