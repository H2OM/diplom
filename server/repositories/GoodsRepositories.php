<?php

namespace app\repositories;

use app\core\Db;

/** Репозиторий для работы с товарами */
class GoodsRepositories {
    public function __construct(private readonly Db $db) {}



}