<?php

namespace Tests\Unit;

use Webulator\Contracts\Dispatcher;
use Webulator\Contracts\Match;
use Webulator\Contracts\Request;
use Webulator\Contracts\RouteCollection;

class DispatcherTest extends BaseTest
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->dispatcher = $this->bootedApp()->resolve(Dispatcher::class);
        $this->request = $this->bootedApp()->resolve(Request::class);
        $this->routeCollection = $this->bootedApp()->resolve(RouteCollection::class);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_dispatch_a_proper_request()
    {
        $this->routeCollection->get("/test", "TestController@testAction");

        $match = $this->dispatcher->dispatch($this->createRequest("GET", "/test"));

        $this->assertInstanceOf(Match::class, $match);

        $this->assertEquals($match::FOUND, $match->status());
        $this->assertEquals("TestController", $match->controller());
        $this->assertEquals("testAction", $match->action());
    }

    /** @test */
    public function it_can_dispatch_a_not_found_request()
    {
        $match = $this->dispatcher->dispatch($this->createRequest("GET", "/impossible"));

        $this->assertInstanceOf(Match::class, $match);
        $this->assertEquals($match::NOT_FOUND, $match->status());
    }

    /** @test */
    public function it_can_dispatch_a_method_not_allowed_request()
    {
        $this->routeCollection->post("/notAllowed", "TestController");

        $match = $this->dispatcher->dispatch($this->createRequest("GET", "/notAllowed"));

        $this->assertInstanceOf(Match::class, $match);
        $this->assertEquals($match::NOT_ALLOWED, $match->status());
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
}