<?php

namespace Webulator\Contracts;

interface Dispatcher
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function dispatch(Request $request);
}
