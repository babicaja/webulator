<?php

namespace Webulator\Contracts;

interface RequestHandler
{
    /**
     * Handle request object with optional route data.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request) : Response;
}