<?php

namespace Kucasoft\HTTP;

use Kucasoft\Contracts\Response as WebulatorResponse;
use Zend\Diactoros\Response as ZendResponse;

class Response extends ZendResponse implements WebulatorResponse
{
}