<?php

namespace app\repositories;

use app\core\Db;

/** Репозиторий для управления товарами */
readonly class GoodsRepository {
    public function __construct(private Db $db) {}



}