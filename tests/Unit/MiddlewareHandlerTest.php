<?php

namespace Tests\Unit;

use Webulator\Contracts\Configuration;
use Webulator\Contracts\MiddlewareHandler;
use Webulator\Contracts\Response;

class MiddlewareHandlerTest extends UnitBase
{
    /**
     * @var MiddlewareHandler
     */
    private $handler;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Response
     */
    private $response;

    /**
     * Resolve middleware handler out of the container.
     *
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->handler = $this->bootedApp()->resolve(MiddlewareHandler::class);
        $this->configuration = $this->bootedApp()->resolve(Configuration::class);
        $this->response =$this->bootedApp()->resolve(Response::class);
    }

    /** @test */
    public function it_can_return_a_response_if_no_middleware_is_defined()
    {
        $response = $this->handler->pass([]);
        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_can_return_a_response_with_defined_middleware()
    {
        $middleware = $this->configuration->get("middleware.namespace", "") . "MiddlewareTest";
        $middlewareMock = $this->getMockBuilder($middleware)->setMethods(["process"])->getMock();

        $responseMock = $this->response->withStatus(201);
        $responseMock->getBody()->write("middleware-test");

        $middlewareMock->method("process")->willReturn($responseMock);

        $this->bootedApp()->bind($middleware, $middlewareMock);

        $response = $this->handler->pass([$middleware]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("middleware-test", $response->getBody());
        $this->assertEquals(201, $response->getStatusCode());
    }
}