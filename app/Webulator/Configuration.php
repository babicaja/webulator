<?php

namespace Webulator;

use Noodlehaus\Config;
use Webulator\Contracts\Configuration as WebulatorConfiguration;
use Webulator\Exceptions\ConfigurationLoadException;

class Configuration extends Config implements WebulatorConfiguration
{
    /**
     * Configuration constructor.
     *
     * @throws ConfigurationLoadException
     */
    public function __construct()
    {
        $defaultPath = rootPath(getenv("CONFIG_PATH") ? : "config");

        try
        {
            parent::__construct($defaultPath);
        }
        catch (\Exception $exception)
        {
            throw new ConfigurationLoadException("There was a problem loading the configuration from path: ${defaultPath}", $exception->getCode(), $exception);
        }
    }
}