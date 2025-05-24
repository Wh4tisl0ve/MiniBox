<?php

namespace MiniBox\Contract;


abstract class AbstractRouter
{
    protected array $routes = [];

    public abstract function get(string $nameRoute);

    public abstract function register(array $handler, string $nameRoute);
}
