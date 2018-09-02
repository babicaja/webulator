<?php

namespace Webulator\Contracts;

interface ServerRequestFactory
{
    /**
     * Captures current PHP environment and request.
     */
    public static function createFromGlobals() : Request;
}