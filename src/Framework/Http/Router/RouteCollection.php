<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\MethodNotAllowedException;

class RouteCollection
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param Route $route
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $methods
     * @param array $tokens
     * @return void
     */
    public function add(
        string $name,
        string $pattern,
        callable $handler,
        array $methods,
        array $tokens = []
    ): void {
        $this->addRoute(new Route($name, $pattern, $handler, $methods, $tokens));
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $tokens
     * @return void
     */
    public function any(
        string $name,
        string $pattern,
        callable $handler,
        array $tokens = []
    ): void {
        $this->addRoute(new Route($name, $pattern, $handler, [], $tokens));
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $tokens
     * @return void
     */
    public function get(
        string $name,
        string $pattern,
        callable $handler,
        array $tokens = []
    ): void {
        $this->addRoute(new Route($name, $pattern, $handler, ['GET'], $tokens));
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $tokens
     * @return void
     */
    public function post(
        string $name,
        string $pattern,
        callable $handler,
        array $tokens = []
    ): void {
        $this->addRoute(new Route($name, $pattern, $handler, ['POST'], $tokens));
    }

    /**
     * @return Route[]
     */
    public function getRoutesForMethod(string $method): array
    {
        $routesForMethod = [];

        foreach ($this->routes as $route) {
            if ($route->getMethods() && \in_array($method, $route->getMethods(), true)) {
                $routesForMethod[] = $route;
            }
        }

        if (empty($routesForMethod)) {
            throw new MethodNotAllowedException($method);
        }

        return $routesForMethod;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
