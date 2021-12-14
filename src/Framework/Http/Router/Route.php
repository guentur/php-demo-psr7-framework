<?php
/**
 * Route
 */

namespace Framework\Http\Router;

class Route
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $pattern;

    /**
     * @var callable
     */
    public $handler;

    /**
     * @var array
     */
    public $methods;

    /**
     * @var array
     */
    public $tokens;

    /**
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $methods
     * @param array $tokens
     */
    public function __construct(
        string $name,
        string $pattern,
        callable $handler,
        array $methods,
        array $tokens = []
    ) {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->methods = $methods;
        $this->tokens = $tokens;
    }

    /**
     * @param array $arguments
     * @return string
     */
    public function getUrl(array $arguments): string
    {
        return preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$arguments) {
            $argument = $matches[1];
            if (!array_key_exists($argument, $arguments)) {
                throw new \InvalidArgumentException('Missing parameter "' . $argument . '"');
            }
            return $arguments[$argument];
        }, $this->pattern);
    }

    /**
     * @return string
     */
    public function getRegexPatternForUrl(): string
    {
        return preg_replace_callback('~\{([^\}]+)\}~', function ($matches) {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^}]+';
            return '(?P<' . $argument . '>' . $replace . ')';
        }, $this->pattern);
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

}