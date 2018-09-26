<?php

namespace Webulator\Utils;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Webulator\Contracts\Configuration;
use Webulator\Contracts\Logger as WebulatorLogger;

class Logger extends Monolog implements WebulatorLogger
{
    /**
     * Logger constructor.
     *
     * @param Configuration $configuration
     * @throws \Exception
     */
    public function __construct(Configuration $configuration)
    {
        parent::__construct("webulator");

        $storage = $configuration->get("storage.path");

        $this->pushHandler(new StreamHandler($storage."/logs/webulator.log", Monolog::DEBUG));
    }
}