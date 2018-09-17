<?php

namespace Webulator\HTTP\Controllers;

class HomeController extends BaseController
{
    /**
     * Shows the welcome page.
     *
     * @return \Webulator\Contracts\Response
     */
    public function welcome()
    {
        $body = $this->template->render("welcome.html", [
            "message" => "This is a simple web application framework based on the leading vendor components of the industry.",
            "appName" => $this->configuration->get("app.name"),
            "appVersion" => $this->configuration->get("app.version")
        ]);

        $this->response()->getBody()->write($body);
        return $this->response();
    }
}