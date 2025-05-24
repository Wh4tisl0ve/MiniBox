<?php


namespace MiniBox\Http;

use MiniBox\Exception\InvalidDataException;
use MiniBox\Exception\ValidationException;
use MiniBox\Http\Exception\ValidationDataException;

class HttpRequest
{
    private string $uri;
    private string $method;
    private array $data;

    public function __construct(array $data, string $uri, string $method)
    {
        $this->data = $data;
        $this->uri = $uri;
        $this->method = $method;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @throws ValidationException|InvalidDataException
     */
    public function validateData(array $requiredField): void
    {
        foreach ($requiredField as $field) {
            if (!isset($this->data[$field])) {
                throw new ValidationDataException("Отсутствует обязательное поле $field");
            }
            if (empty($this->data[$field])) {
                throw new InvalidDataException("Данные для поля $field не могут быть пустыми");
            }
        }
    }
}
