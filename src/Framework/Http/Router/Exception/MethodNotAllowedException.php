<?php
/**
 * MethodNotAllowedException
 */

namespace Framework\Http\Router\Exception;

class MethodNotAllowedException extends \LogicException
{
    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var array
     */
    private $allowedMethods;

    /**
     * @param string $requestMethod
     * @param array $allowedMethods
     */
    public function __construct(string $requestMethod, array $allowedMethods = [])
    {
        if (empty($allowedMethods)) {
            parent::__construct("Request Method $requestMethod is not allowed in any Routes.", 405);
        } else {
            parent::__construct("Request Method $requestMethod is not allowed. Allowed methods: " . implode(', ', $allowedMethods), 405);
        }
        $this->allowedMethods = $allowedMethods;
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return string
     */
    public function setRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @return array
     */
    public function setAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}