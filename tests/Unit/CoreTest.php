<?php

namespace Tests\Unit;

use Kucasoft\Application;
use Kucasoft\Contracts\MiddlewareHandler;
use Kucasoft\Contracts\Request;
use Kucasoft\Contracts\RequestHandler;
use Kucasoft\Contracts\Response;
use Tests\Traits\MakesApp;

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
        $response = $app->resolve($component);

        $this->assertInstanceOf($component, $response, "The application does not have a ${component} component.");
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