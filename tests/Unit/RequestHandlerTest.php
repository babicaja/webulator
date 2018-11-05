<?php

namespace Tests\Unit;

use Webulator\Contracts\Configuration;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\Response;
use Webulator\Contracts\RouteCollection;

class RequestHandlerTest extends UnitBase
{
    /**
     * @var RequestHandler
     */
    private $handler;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Resolve the request and request handler out of the container.
     * 
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->request = $this->bootedApp()->resolve(Request::class);
        $this->response = $this->bootedApp()->resolve(Response::class);
        $this->routeCollection = $this->bootedApp()->resolve(RouteCollection::class);
        $this->configuration = $this->bootedApp()->resolve(Configuration::class);

        // Reset routes on every run.
        $this->routeCollection->reset();
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_handle_a_request_to_a_non_existing_route()
    {
        $this->resolveHandler();
        $request = $this->createRequest("GET", "/doesnotexist");
        $response = $this->handler->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_handle_a_request_to_an_existing_route_with_a_wrong_method()
    {
        $this->routeCollection->get("/test", "TestController@home");
        $this->resolveHandler(); // Needs to be done after route creation.

        $request = $this->createRequest("POST", "/test");
        $response = $this->handler->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_handle_a_valid_request()
    {
        $identifier = $this->configuration->get("controller.namespace") . "TestController";

        $controllerMock = $this->getMockBuilder($identifier)->setMethods(["test"])->getMock();
        $controllerMock->method("test")->willReturn($this->response);

        $this->bootedApp()->bind($identifier, $controllerMock);

        $this->routeCollection->get("/tester", "TestController@test");
        $this->resolveHandler(); // Needs to be done after route creation.

        $request = $this->createRequest("GET", "/tester");
        $response = $this->handler->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Creates a simple request with given method and uri.
     *
     * @param $method
     * @param $uri
     * @return Request
     */
    private function createRequest($method, $uri)
    {
        $currentUri = $this->request->getUri();
        $newUri = $currentUri->withPath($uri);

        return $this->request->withMethod($method)->withUri($newUri);
    }

    /**
     * Resolves the handler out of the container. This should be used after the routes are defined.
     *
     * @throws \Exception
     */
    protected function resolveHandler(): void
    {
        $this->handler = $this->bootedApp()->resolve(RequestHandler::class);
    }
}