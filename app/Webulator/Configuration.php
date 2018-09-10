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
     * @param $path
     * @throws ConfigurationLoadException
     */
    public function __construct($path)
    {
        try
        {
            parent::__construct($path);
        }
        catch (\Exception $exception)
        {
            throw new ConfigurationLoadException("There was a problem loading the configuration from path: ${path}", $exception->getCode(), $exception);
        }
    }
}