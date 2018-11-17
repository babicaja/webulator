<?php

namespace Tests\Unit;

use Webulator\Application;
use Webulator\Contracts\Configuration;
use Webulator\Contracts\Database;
use Webulator\Contracts\HTTPClient;
use Webulator\Contracts\Logger;
use Webulator\Contracts\MiddlewareHandler;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;
use Webulator\Contracts\ResponseEmitter;
use Webulator\Router\Dispatcher;
use Webulator\Router\Match;
use Webulator\Router\RouteCollection;

class CoreTest extends UnitBase
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
     * @throws \ReflectionException
     */
    public function it_can_set_middleware()
    {
        $app = $this->bootedApp();
        $app->pipe(["SomeClassName"]);

        // A bit of reflection because we want to check a private property.
        $reflection = new \ReflectionClass(Application::class);
        $middlewareProperty = $reflection->getProperty("middleware");
        $middlewareProperty->setAccessible(true);

        $this->assertEquals(["SomeClassName"], $middlewareProperty->getValue($app));
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_set_routes()
    {
        $app = $this->bootedApp();
        $app->routes()->get("test", "TestController@test");

        $collection = $app->routes()->retrieve();

        $this->assertArrayHasKey("test", $collection[0]["GET"]);

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
            "Dispatcher Component" => [Dispatcher::class],
            "RouteCollection Component" => [RouteCollection::class],
            "Match Component" => [Match::class],
            "Configuration Component" => [Configuration::class],
            "Logger Component" => [Logger::class],
            "Database Component" => [Database::class],
            "HTTPClient Component" => [HTTPClient::class],
            "Response Emitter Component" => [ResponseEmitter::class],
        ];
    }

    /**
     * @test
     * @throws \Exception
     * @runInSeparateProcess
     */
    public function it_can_make_a_run()
    {
        $this->bootedApp()->run();
        $this->expectOutputRegex("/The page you are looking for does not exist./");
    }
}