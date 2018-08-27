<?php

namespace Tests\Unit;

use Kucasoft\Application;

class CoreTest extends BaseTest
{
    /** @test */
    public function it_can_bootstrap_the_application()
    {
        $this->assertInstanceOf(Application::class, $this->app(), "The application could not be bootstrapped.");
    }

    /** @test */
    public function it_has_the_app_name_dotenv_value()
    {
        $this->assertTrue(is_string(getenv("APP_NAME")), "The application name is not set.");
    }

    /** @test */
    public function it_has_the_app_version_dotenv_value()
    {
        $this->assertTrue(is_string(getenv("APP_VERSION")), "The application version is not set.");
    }
}