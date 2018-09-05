<?php

namespace Webulator\HTTP;

use Webulator\Contracts\Response as WebulatorResponse;
use Zend\Diactoros\Response as ZendResponse;

class Response extends ZendResponse implements WebulatorResponse
{
    public function __construct($body = 'php://memory', int $status = 200, array $headers = [])
    {
        parent::__construct($body, $status, $headers);
    }
}