<?php

namespace Webulator\Contracts;

use Webulator\Router\Match;

interface Dispatcher
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function dispatch(Request $request) : Match;
}
