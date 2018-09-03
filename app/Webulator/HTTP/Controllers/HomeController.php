<?php

namespace Webulator\HTTP\Controllers;

class HomeController extends BaseController
{
    /**
     * @return mixed|\Webulator\Contracts\Response
     */
    public function welcome()
    {
       $this->response()->getBody()->write("This is the Webulator app, welcome!");
       return $this->response();
    }
}