<?php

namespace MiniBox\Container;

use MiniBox\Container\Exception\FailReadServicesConfigException;
use MiniBox\Container\Exception\ServiceExistsException;
use MiniBox\Container\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];
    private array $instances = [];

    /**
     * @throws ServiceNotFoundException
     */
    public function get(string $id): mixed
    {
        if ($this->hasInstance($id)) {
            return $this->instances[$id];
        }
        if ($this->has($id)) {
            $instance = $this->services[$id]($this);
            $this->instances[$id] = $instance;
            return $instance;
        }
        throw new ServiceNotFoundException("Зависимость с именем $id не найдена");
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @throws ServiceExistsException
     */
    public function register(string $name, callable $factoryCallback): void
    {
        if ($this->has($name)) {
            throw new ServiceExistsException("Зависимость с именем $name уже существует");
        }
        $this->services[$name] = $factoryCallback;
    }

    /**
     * @throws ServiceExistsException|FailReadServicesConfigException
     */
    public function compile(string $filename): void
    {
        if (!file_exists($filename))
            throw new FailReadServicesConfigException("Не найден конфигурационный файл " . $filename);

        $services = require $filename;

        foreach ($services as $name => $callable) {
            $this->register($name, $callable);
        }
    }

    private function hasInstance($name): bool
    {
        return isset($this->instances[$name]);
    }
}