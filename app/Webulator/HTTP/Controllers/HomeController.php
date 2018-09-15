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
        $body = $this->template->render("welcome.html", ["message" => "Welcome to the Webulator Application, have fun."]);
        $this->response()->getBody()->write($body);
        return $this->response();
    }
}