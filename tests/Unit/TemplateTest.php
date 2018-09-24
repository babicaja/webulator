<?php

namespace Tests\Unit;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Webulator\Contracts\Configuration;
use Webulator\Exceptions\ContainerResolveException;
use Webulator\Exceptions\TemplateSetupException;

class TemplateTest extends BaseTest
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var Configuration
     */
    private $originalConfig;

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * Creates a virtual file system, and makes a copy of the original configuration.
     *
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        // Let's prepare a fake file system.
        $structure = [
            "views" => [
                "test.html" => "Hello {{ name }}"
            ],
            "storage" => []
        ];

        $this->root = vfsStream::setup("root", 777, $structure);

        // We will need the config to change the templates folder on the fly.
        $this->config = $this->bootedApp()->resolve(Configuration::class);
        $this->originalConfig = $this->config;
    }

    /**
     * Restores the original configuration.
     */
    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->config = $this->originalConfig;
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_handle_a_bad_directory_path()
    {
        $this->config->set('template.path', $this->root->url().'/nonexistent');

        try
        {
            $this->bootedApp()->make(\Webulator\Contracts\Template::class);
        }
        catch (ContainerResolveException $exception)
        {
            $this->assertInstanceOf(TemplateSetupException::class, $exception->getPrevious());
        }
    }

    /**
     * @test
     * @throws \Exception
     * @expectedException \Webulator\Exceptions\TemplateRenderException
     */
    public function it_can_handle_a_nonexistent_file()
    {
        $this->config->set('template.path', $this->root->url()."/views");
        $template = $this->bootedApp()->make(\Webulator\Contracts\Template::class);
        $template->render("nonexistent.html");
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_render_a_file()
    {
        $this->config->set('template.path', $this->root->url()."/views");

        $template = $this->bootedApp()->make(\Webulator\Contracts\Template::class);
        $render = $template->render("test.html", ["name" => "test"]);

        $this->assertEquals("Hello test", $render);
    }
}