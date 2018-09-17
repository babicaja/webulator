<?php

namespace Webulator\HTTP\Controllers;

use Webulator\Contracts\Response;

final class RouteErrorController extends BaseController
{
    /**
     * Shows the not found page.
     *
     * @return Response
     */
    public function notFound()
    {
        $body = $this->template->render("error.html", [
            "message" => "The page you are looking for does not exist."
        ]);

        $response = $this->response()->withStatus(404);
        $response->getBody()->write($body);

        return $response;
    }

    /**
     * Shows the not allowed page.
     *
     * @return Response
     */
    public function notAllowed()
    {
        $body = $this->template->render("error.html", [
            "message" => "This method is not allowed."
        ]);

        $response = $this->response()->withStatus(405);
        $response->getBody()->write($body);

        return $response;
    }
}