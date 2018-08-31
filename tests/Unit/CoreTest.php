<?php

namespace Tests\Unit;

use Webulator\Application;
use Webulator\Contracts\MiddlewareHandler;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;

class CoreTest extends BaseTest
{
    /** @test */
    public function it_can_bootstrap_the_application()
    {
        $this->assertInstanceOf(Application::class, $this->bootedApp(), "The application could not be bootstrapped.");
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

    /**
     * @test
     * @dataProvider components
     * @param $component
     * @throws \Exception
     */
    public function it_can_resolve_all_the_app_components($component)
    {
        $app = $this->bootedApp();
        $resolved = $app->resolve($component);

        $this->assertInstanceOf($component, $resolved, "The application does not have a ${component} component.");
    }

    /**
     * Building blocks of the application.
     *
     * @return array
     */
    public function components()
    {
        return [
            "Request Component" => [Request::class],
            "Response Component" => [Response::class],
            "Request Handler Component" => [RequestHandler::class],
            "Middleware Handler Component" => [MiddlewareHandler::class],
        ];
    }
}