<?php

namespace App\Exceptions;

class HttpException extends \Exception
{
    public function __construct(string $message, int $statusCode = 400)
    {
        parent::__construct($message, $statusCode);
    }
}
