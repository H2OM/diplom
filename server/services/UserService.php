<?php

namespace app\services;

use app\core\Validator;
use app\repositories\FavoritesRepository;
use app\repositories\OrdersRepository;
use app\repositories\UserRepository;
use Exception;

/** Сервис для управления с пользователями */
readonly class UserService {
    public function __construct(
        private UserRepository $userRepository,
        private FavoritesRepository $favoritesRepository,
        private OrdersRepository $ordersRepository,
        private Validator $validator,
    ) {}

    /**
     * Авторизация пользователя
     *
     * @param string $password
     * @param string $phone
     * @return array
     * @throws Exception
     */
    public function signIn(string $password, string $phone): array {
        $validateData = $this->validator->validate(
            data: [
                'password' => $password,
                'phone' => $phone
            ],
            rules: [
                'password' => ['required', 'password'],
                'phone' => ['required', 'phone'],
            ]
        );

        if (!$validateData) {
            throw new Exception('Ошибка валидации');
        }

        $user = $this->userRepository->getUserByPhone($phone);

        if(empty($user) || !password_verify($validateData['password'], $user['password'])) {
            throw new Exception('Неверный логин или пароль');
        }

        return $user;
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

        $prepareUserData = $this->prepareUserData($validateData);

        $insertId = $this->userRepository->insertNewUser($prepareUserData);

        if(!$insertId) {
            throw new Exception('Произошла ошибка при добавлении нового пользователя');
        }

        return [
            'id' => $insertId,
            ...$prepareUserData
        ];
    }

    /**
     * Редактирование пользователя
     *
     * @param array $userData
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function editUser(array $userData, int $userId): array {
        $validateData = $this->validator->validate(data: $userData, rules: [
            'first_name'  => ['required', 'name'],
            'second_name' => ['required', 'name'],
            'phone'       => ['required', 'phone'],
            'age'         => ['required', 'age'],
            'gender'      => ['required', 'gender'],
            'email'       => ['required', 'email'],
        ]);

        if(!$validateData) {
            throw new Exception($this->validator->formatErrors());
        }

        $prepareUserData = $this->prepareUserData($validateData);

        $result = $this->userRepository->editUser($userId, $prepareUserData);

        if(!$result) {
            throw new Exception('Не удалось обновить данные пользователя');
        }

        return $prepareUserData;
    }

    /**
     * Получение 'избранного' пользователя
     *
     * @param int $userId
     * @return array
     */
    public function getFavorites(int $userId): array {
        return $this->favoritesRepository->getFavorites($userId);
    }

    /**
     * Редактирование 'избранного' пользователя
     *
     * @param int $userId
     * @param string $productId
     * @param string $action
     * @return string|false
     * @throws Exception
     */
    public function changeFavorites(int $userId, string $productId, string $action): string|false {
        return match ($action) {
            'set' => $this->favoritesRepository->setFavorite($userId, $productId),
            'unset' => $this->favoritesRepository->unsetFavorite($userId, $productId),
            default => throw new \Exception('Не известное действие'),
        };
    }

    /**
     * Получение всех заказов пользователя
     *
     * @param int $userId
     * @return array
     */
    public function getUserOrders(int $userId): array {
        $orders = $this->ordersRepository->getOrdersByUserId($userId);

        $result = [];

        foreach($orders as $order) {
            $orderId = $order['id'];

            $product = [];

            foreach ($order as $field => $value) {
                if(in_array($field, ['id', 'number', 'status', 'user_id', 'date', 'change_date', 'delivery_date', 'comment'])) {
                     $result[$orderId][$field] = $value;
                } else {
                    $product[$field] = $value;
                }
            }

            if(!isset($result[$orderId]['goods'])) {
                $result[$orderId]['goods'] = [];
            }

            $result[$orderId]['goods'][] = $product;
        }

        return $result;
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