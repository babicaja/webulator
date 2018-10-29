<?php

namespace Webulator\Contracts;

interface ErrorHandler
{
    /**
     * Callback for the set error handler.
     *
     * @param $level
     * @param $message
     * @param string $file
     * @param int $line
     */
    public function handleError($level, $message, $file = '', $line = 0);
}