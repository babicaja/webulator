<?php

namespace Webulator\Utils;

use Webulator\Contracts\ErrorHandler as WebulatorErrorHandler;
use Webulator\Exceptions\ErrorException;

class ErrorHandler implements WebulatorErrorHandler
{
    /**
     * ErrorHandler constructor.
     */
    public function __construct()
    {
        error_reporting(-1);
        set_error_handler([$this, "handleError"]);
    }

    /**
     * Callback for the set error handler.
     *
     * @param $level
     * @param $message
     * @param string $file
     * @param int $line
     * @throws ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0)
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message);
        }
    }
}