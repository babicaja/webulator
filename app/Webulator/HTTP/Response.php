<?php

namespace Webulator\HTTP;

use Webulator\Contracts\Response as WebulatorResponse;
use Zend\Diactoros\Response as ZendResponse;

class Response extends ZendResponse implements WebulatorResponse
{
}