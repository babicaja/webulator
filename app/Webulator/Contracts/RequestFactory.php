<?php

namespace Webulator\Contracts;

interface RequestFactory
{
    /**
     * Captures current PHP environment and request.
     */
    public static function createFromGlobals() : Request;
}