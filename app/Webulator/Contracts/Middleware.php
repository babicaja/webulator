<?php

namespace Webulator\Contracts;

interface Middleware
{
    /**
     * @return mixed
     */
    public function process();
}