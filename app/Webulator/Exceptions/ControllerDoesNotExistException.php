<?php

namespace Webulator\Exceptions;

use Throwable;

class ControllerDoesNotExistException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}