<?php

namespace app\services;

use app\core\Session;

/** Сервис для управления корзиной покупок */
readonly class BasketService {
    public function __construct(private Session $session) {}

}