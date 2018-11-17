<?php

namespace Webulator\Utils;

use GuzzleHttp\Client;
use Webulator\Contracts\HTTPClient as WebulatorHTTPClient;

class HTTPClient extends Client implements WebulatorHTTPClient
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
}