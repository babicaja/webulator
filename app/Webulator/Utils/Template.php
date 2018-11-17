<?php

namespace Webulator\Utils;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Umpirsky\Twig\Extension\PhpFunctionExtension;
use Webulator\Contracts\Configuration;
use Webulator\Contracts\Template as WebulatorTemplate;
use Webulator\Exceptions\TemplateRenderException;
use Webulator\Exceptions\TemplateSetupException;

class Template extends Twig_Environment implements WebulatorTemplate
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Template constructor.
     * @param Configuration $configuration
     * @throws TemplateSetupException
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        $loader = $this->createLoader();
        $options = $this->createOptions();

        parent::__construct($loader, $options);

        $extension = new PhpFunctionExtension();
        $extension->allowFunction("asset");

        $this->addExtension($extension);
    }

    /**
     * Render a template.
     *
     * @param string $view
     * @param array $parameters
     * @return mixed|string
     *
     * @throws TemplateRenderException
     */
    public function render($view, array $parameters = [])
    {
        try
        {
            return parent::render($view, $parameters);
        }
        catch (\Exception $exception)
        {
            throw new TemplateRenderException("The view ${view} could not be rendered.", $exception->getCode(), $exception);
        }
    }

    /**
     * Creates twig loader.
     *
     * @return Twig_Loader_Filesystem
     * @throws TemplateSetupException
     */
    private function createLoader()
    {
        try
        {
            return new Twig_Loader_Filesystem($this->configuration->get('template.path'));
        }
        catch (\Exception $exception)
        {
            throw new TemplateSetupException("There was a problem setting up the templating engine", $exception->getCode(), $exception);
        }
    }

    /**
     * Creates twig options.
     *
     * @return array
     */
    private function createOptions()
    {
        return [
            'cache' => $this->isDebugOn() ? false : $this->configuration->get("storage.path")."/cache"
        ];
    }

    /**
     * @return bool
     */
    private function isDebugOn()
    {
        $debug = getenv("DEBUG");

        if ($debug == "false") $debug = false;

        return (bool) $debug;
    }
}