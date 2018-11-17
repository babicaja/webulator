<?php

namespace Webulator\HTTP;

use Webulator\Contracts\ResponseEmitter as WebulatorResponseEmitter;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class ResponseEmitter extends SapiEmitter implements WebulatorResponseEmitter
{

}