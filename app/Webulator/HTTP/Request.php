<?php

namespace Webulator\HTTP;

use Webulator\Contracts\Request as WebulatorRequest;
use Zend\Diactoros\ServerRequest;

class Request extends ServerRequest implements WebulatorRequest
{
}