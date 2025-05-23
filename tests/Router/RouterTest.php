<?php

namespace Tests\Router;


use MiniBox\Contract\AbstractRouter;
use MiniBox\Router\Exception\RouteExistsException;
use MiniBox\Router\Exception\RouteNotFoundException;
use MiniBox\Router\HttpRouter;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private ?AbstractRouter $router;

    public function setUp(): void
    {
        $this->router = new HttpRouter('');
    }

    public function testSuccessRegisterRoute()
    {
        $this->router->register(['handler', 'get'], 'testName');
        $this->assertTrue(true);
    }

    public function testRegisterExistsRoute()
    {
        $this->router->register(['handler', 'get'], 'testName');

        $this->expectException(RouteExistsException::class);

        $this->router->register(['handler', 'get'], 'testName');
        $this->assertTrue(true);
    }

    public function testSuccessGetRoute()
    {
        $this->router->register(['handler', 'get'], 'testName');

        $handler = $this->router->get('testName');

        $expectedResult = [
            "handler" => ['handler', 'get'],
            "params" => []
        ];

        $this->assertEquals($expectedResult, $handler);
    }

    public function testGetNotExistsRoute()
    {
        $this->expectException(RouteNotFoundException::class);

        $this->router->get('testName');

        $this->assertTrue(true);
    }

    public function tearDown(): void
    {
        $this->router = null;
    }
}