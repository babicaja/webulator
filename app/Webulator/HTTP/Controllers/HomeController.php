<?php

namespace Webulator\HTTP\Controllers;

use Webulator\Contracts\Response;

class HomeController extends BaseController
{
    /**
     * Shows the welcome page.
     *
     * @return \Webulator\Contracts\Response
     */
    public function welcome()
    {
       $this->response()->getBody()->write("This is the Webulator app, welcome!");
       return $this->response();
    }
}