<?php

namespace app\core\enums;

enum ResponseMessage: string {
    case ERROR_PRODUCT_NOT_FOUND = 'Товар не найден';
    case ERROR_NOT_ENOUGH_DATA = 'Не достаточно данных';
    case ERROR_DATA = 'Некорректные данные';
    case ERROR_GET_DATA = 'Ошибка при получении данных';
    case ERROR_NEW_USER = 'Ошибка при добавлении нового пользователя';
    case ERROR_AUTH = 'Неверный логин или пароль';
    case ERROR_UPDATE = 'Не удалось обновить данные';
    case ERROR_NOT_AUTH = 'Не авторизирован';
    case SUCCESS_SUBSCRIBE = 'Успешная подписка на обновления';
    case SUCCESS_FORM = 'Ваша заявка в обработке';
    case SUCCESS_AUTH = 'Успешная авторизация';
    case SUCCESS_LOGOUT = 'Успешная деавторизация';
    case SUCCESS_ADD = 'Успешное добавление';
    case SUCCESS_REMOVE = 'Успешное удаление';
    case USER_ALREADY = 'Пользователь уже авторизован';
}