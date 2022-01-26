<?php

declare(strict_types=1);

namespace App\Http\Action;

use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
{
    public function __invoke(): HtmlResponse
    {
        return new HtmlResponse('I am a simple site');
    }
}
