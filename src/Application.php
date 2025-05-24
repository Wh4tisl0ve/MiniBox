<?php

namespace MiniBox;

use MiniBox\ArgumentsResolver\ArgumentsResolver;
use MiniBox\Container\Container;
use MiniBox\Contract\AbstractRouter;
use MiniBox\Http\HttpRequest;
use MiniBox\Http\Response\HttpResponse;
use MiniBox\Router\HttpRouter;
use ReflectionMethod;

class Application extends Container
{
    private AbstractRouter $router;

    public function __construct(
        ?string $configServicesPath = '../config/services.php',
        ?string $configRoutesPath = '../config/routes.php')
    {
        $this->compile($configServicesPath);
        $this->registerDepends();
        $this->router->build($configRoutesPath);
    }

    private function registerDepends(): void
    {
        $this->router = new HttpRouter();
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        $route = $this->router->get($httpRequest->getUri(), $httpRequest->getMethod());

        [$controllerClass, $methodName] = $route['handler'];

        $controllerInstance = $this->get($controllerClass);
        $methodParams = (new ReflectionMethod($controllerInstance, $methodName))->getParameters();

        $args = [$httpRequest, $route['params']];

        $methodArguments = ArgumentsResolver::resolveArguments($methodParams, $args);

        return call_user_func_array([$controllerInstance, $methodName], $methodArguments);
    }

    public function registerExceptionHandler(callable $exceptionHandler): void
    {
        set_exception_handler($exceptionHandler);
    }
}