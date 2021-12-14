<?php

namespace Tests\Framework\Http;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\MethodNotAllowedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response\JsonResponse;

class RouterTest extends TestCase
{
    /**
     * @return void
     */
    public function testCorrectMethod(): void
    {
        $routes = new RouteCollection();

        $routes->get($nameGet = 'blog', '/blog', $handlerGet = $this->callableForTest());
        $routes->post($namePost = 'blog_edit', '/blog', $handlerPost = $this->callableForTest());

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        self::assertEquals($nameGet, $result->getName());
        self::assertEquals($handlerGet, $result->getHandler());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        self::assertEquals($namePost, $result->getName());
        self::assertEquals($handlerPost, $result->getHandler());
    }

    public function testMissingMethod(): void
    {
        $routes = new RouteCollection();

        $routes->post('blog', '/blog', $this->callableForTest());

        $router = new Router($routes);

        $this->expectException(MethodNotAllowedException::class);
        $router->match($this->buildRequest('DELETE', '/blog'));
    }

    public function testCorrectAttributes(): void
    {
        $routes = new RouteCollection();

        $routes->get($name = 'blog_show', '/blog/{id}', $this->callableForTest(), ['id' => '\d+']);

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog/5'));

        self::assertEquals($name, $result->getName());
        self::assertEquals(['id' => '5'], $result->getAttributes());
    }

    public function testIncorrectAttributes(): void
    {
        $routes = new RouteCollection();

        $routes->get('blog_show', '/blog/{id}', $this->callableForTest(), ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('GET', '/blog/slug'));
    }

    public function testGenerate(): void
    {
        $routes = new RouteCollection();

        $routes->get('blog', '/blog', $this->callableForTest());
        $routes->get('blog_show', '/blog/{id}', $this->callableForTest(), ['id' => '\d+']);

        $router = new Router($routes);

        self::assertEquals('/blog', $router->generate('blog'));
        self::assertEquals('/blog/5', $router->generate('blog_show', ['id' => 5]));
    }

    public function testGenerateMissingAttributes(): void
    {
        $routes = new RouteCollection();

        $routes->get($name = 'blog_show', '/blog/{id}', $this->callableForTest(), ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(\InvalidArgumentException::class);
        $router->generate('blog_show', ['slug' => 'post']);
    }

    /**
     * @param $method
     * @param $uri
     * @return ServerRequest
     */
    private function buildRequest($method, $uri): ServerRequest
    {
        return (new ServerRequest())
            ->withMethod($method)
            ->withUri(new Uri($uri));
    }

    /**
     * @return callable
     */
    private function callableForTest(): callable
    {
        return function () {
            return new JsonResponse('I am a simple site');
        };
    }
}