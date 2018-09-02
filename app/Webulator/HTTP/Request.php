<?php

namespace Webulator\HTTP;

use Webulator\Contracts\Request as WebulatorRequest;
use Zend\Diactoros\ServerRequest;

class Request extends ServerRequest implements WebulatorRequest
{
    public function __construct(array $serverParams = [], array $uploadedFiles = [], $uri = null, string $method = null, $body = 'php://input', array $headers = [], array $cookies = [], array $queryParams = [], $parsedBody = null, string $protocol = '1.1')
    {
//        $body = "php://memory";
        parent::__construct($serverParams, $uploadedFiles, $uri, $method, $body, $headers, $cookies, $queryParams, $parsedBody, $protocol);
    }
}