<?php

namespace app\core;

/** Фасад для DI контейнера */
class App {
    /** @var Container DI контейнер */
    private static Container $container;

    /**
     *  Инициализация контейнера
     *
     * @param Container $container
     * @return void
     */
    public static function init(Container $container): void
    {
        self::$container = $container;
    }

    /**
     * Вызов контейнера
     *
     * @return Container
     */
    public static function container(): Container {
        return self::$container;
    }

    /**
     * Универсальный метод для вызова класса
     *
     * @param string $class
     * @return mixed
     */
    public static function get(string $class): mixed {
        return self::$container->get($class);
    }

    /**
     * Вызов Db
     *
     * @return Db
     */
    public static function db(): Db {
        return self::$container->get(Db::class);
    }

    /**
     * Вызов Logger
     *
     * @return Logger
     */
    public static function logger(): Logger {
        return self::$container->get(Logger::class);
    }

    /**
     * Вызов Validator
     *
     * @return Validator
     */
    public static function validator(): Validator {
        return self::$container->get(Validator::class);
    }

    /**
     * Вызов Request
     *
     * @return Request
     */
    public static function request(): Request {
        return self::$container->get(Request::class);
    }

    /**
     * Вызов Session
     *
     * @return Session
     */
    public static function session(): Session {
        return self::$container->get(Session::class);
    }
}