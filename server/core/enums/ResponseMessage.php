<?php

namespace app\core\enums;

enum ResponseMessage: string {
    case NOT_ENOUGH_DATA = 'Не достаточно данных';
    case PRODUCT_NOT_FOUND = 'Товар не найден';
}