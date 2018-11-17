<?php

namespace Webulator\Middleware;

use Webulator\Contracts\Middleware;
use Webulator\Traits\HasAppComponents;

abstract class BaseMiddleware implements Middleware
{
    use HasAppComponents;
}