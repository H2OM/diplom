<?php

namespace app\controllers;

use app\core\Request;
use app\core\Response;
use app\core\Validator;

/** Контролер для управления обратной связи */
readonly class CallbackController {
    public function __construct(private Validator $validator, private Request $request) {}

    /**
     * Обработка формы подписки на новости
     *
     * @return Response
     */
    protected function mailSubscribeAction(): Response {
        $data = $this->validator->validate(data: $this->request->post(), rules: [
            'email' => ['required', 'email'],
        ]);

        if(!$data)
            return Response::json(data: ['error'=> true, 'message' => 'Неверные данные'], status: 400);

        return Response::json(data: ['success' => true, 'message' => 'Успешно']);
    }

    /**
     * Обработка формы обратной связи
     *
     * @return Response
     */
    protected function formCallbackAction(): Response {
        $data = $this->validator->validate(data: $this->request->post(), rules: [
            'email'      => ['required', 'email'],
            'first_name' => ['required', 'name'],
            'title'      => ['required', 'text'],
            'message'    => ['required', 'text'],
        ]);

        if(!$data)
            return Response::json(data: ['error'=> true, 'message' => 'Неверные данные'], status: 400);

        return Response::json(data: ['success' => true, 'message' => 'Успешная отправка']);
    }
}