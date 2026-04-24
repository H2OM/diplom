<?php

namespace app\core;

/** Формирование ответов сервера */
class Response {
    protected string $content;
    protected int $status;
    protected array $headers = [];

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * Отправка сформированного ответа
     *
     * @return void
     */
    public function send(): void {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
    }

    /**
     * Формирование JSON ответа
     *
     * @param mixed $data
     * @param int $status
     * @return self
     */
    public static function json(mixed $data, int $status = 200): self {
        return new self(
            json_encode($data),
            $status,
            ['Content-Type' => 'application/json']
        );
    }
}