<?php

namespace app\services;

use app\core\Session;
use app\core\Validator;
use app\repositories\UserRepository;
use Exception;

/** Сервис для управления с пользователями */
readonly class UserService {
    public function __construct(
        private UserRepository $users,
        private Validator $validator,
        private Session $session
    ) {}

    /**
     * Авторизация пользователя
     *
     * @param string $password
     * @param string $phone
     * @return bool
     * @throws Exception
     */
    public function signIn(string $password, string $phone): bool {
        $validateData = $this->validator->validate(data: [$password, $phone], rules: [
            'password' => ['required', 'password'],
            'phone'    => ['required', 'phone'],
        ]);

        if(!$validateData) {
            throw new Exception('Ошибка валидации');
        }

        $user = $this->users->getUserByPhone($phone);

        if(empty($user) || !password_verify($validateData['password'], $user['password'])) {
            throw new Exception('Неверный логин или пароль');
        }

        $this->session->set('user', $user);

        return true;
    }

    /**
     * Регистрация пользователя
     *
     * @param array $userData
     * @return array
     * @throws Exception
     */
    public function signUp(array $userData): array {
        $rules = [
            'first_name'  => ['required', 'name'],
            'second_name' => ['required', 'name'],
            'phone'       => ['required', 'phone'],
            'password'    => ['required', 'password', 'confirmed'],
            'age'         => ['required', 'age'],
            'gender'      => ['required', 'gender'],
            'email'       => ['required', 'email'],
        ];

        $validateData = $this->validator->validate($userData, $rules);

        if(!$validateData) {
            throw new Exception($this->validator->formatErrors());
        }

        $prepareUserData = $this->prepareUserData($userData);

        $insertId = $this->users->insertNewUser($prepareUserData);

        if(!$insertId) {
            throw new Exception('Произошла ошибка при добавлении нового пользователя');
        }

        return [
            'id' => $insertId,
            ...$prepareUserData
        ];
    }

    /**
     * Подготовка данных для вставки пользователя
     *
     * @param array $data
     * @return array
     */
    private function prepareUserData(array $data): array {
        return [
            'first_name'  => ucfirst($data['first_name']),
            'second_name' => ucfirst($data['second_name']),
            'phone'       => $this->normalizePhone($data['phone']),
            'password'    => password_hash($data['password'], PASSWORD_DEFAULT),
            'age'         => (int)$data['age'],
            'gender'      => $data['gender'],
            'email'       => strtolower($data['email']),
        ];
    }

    /**
     * Функция для нормализации телефонного номера
     *
     * @param string $phone
     * @return string
     */
    private function normalizePhone(string $phone): string {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}