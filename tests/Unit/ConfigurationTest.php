<?php

namespace Tests\Unit;

use Webulator\Contracts\Configuration;

class ConfigurationTest extends BaseTest
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->config = $this->bootedApp()->resolve(Configuration::class);
    }

    /**
     * @test
     * @expectedException \Webulator\Exceptions\ConfigurationLoadException
     */
    public function it_will_fail_on_load_if_the_path_is_not_valid()
    {
        $this->config::load("someInvalidPath");
    }

    /** @test */
    public function it_can_set_and_get_a_value()
    {
        $this->config->set("test", "value");
        $this->assertEquals("value", $this->config->get("test"));
    }
}