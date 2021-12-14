<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\MethodNotAllowedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Result;

class Router
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result
    {
        $routes = $this->routes->getRoutesForMethod($request->getMethod());
        foreach ($routes as $route) {
            $pattern = $route->getRegexPatternForUrl();

            $path = $request->getUri()->getPath();
            if (preg_match('~^' . $pattern . '$~i', $path, $matches)) {
                return new Result(
                    $route->name,
                    $route->handler,
                    array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
                );
            }
        }

        throw new RequestNotMatchedException($request);
    }

    /**
     * @param $name
     * @param array $params
     * @return string
     */
    public function generate($name, array $params = []): string
    {
        $arguments = array_filter($params);

        foreach ($this->routes->getRoutes() as $route) {
            if ($name !== $route->name) {
                continue;
            }

            return $route->getUrl($arguments);
        }

        throw new RouteNotFoundException($name, $params);
    }
}
