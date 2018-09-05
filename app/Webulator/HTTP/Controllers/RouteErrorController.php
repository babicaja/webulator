<?php

namespace Webulator\HTTP\Controllers;

use Webulator\Contracts\Response;

class RouteErrorController extends BaseController
{
    /**
     * Shows the not found page.
     *
     * @return Response
     */
    public function notFound()
    {
        $response = $this->response()->withStatus(404);
        $response->getBody()->write("The page you are looking for does not exist.");

        return $response;
    }

    /**
     * Shows the not allowed page.
     *
     * @return Response
     */
    public function notAllowed()
    {
        $response = $this->response()->withStatus(405);
        $response->getBody()->write("This method is not allowed for this page.");

        return $response;
    }
}