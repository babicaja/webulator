<?php

namespace Kucasoft\HTTP;

use Kucasoft\Contracts\Request as WebulatorRequest;
use Zend\Diactoros\ServerRequest;

class Request extends ServerRequest implements WebulatorRequest
{
}