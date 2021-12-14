<?php
/**
 * Result
 */

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;

class Result
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param string $name
     * @param callable $handler
     * @param array $attributes
     */
    public function __construct(
        string $name,
        callable $handler,
        array $attributes
    ) {
        $this->name = $name;
        $this->handler = $handler;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}