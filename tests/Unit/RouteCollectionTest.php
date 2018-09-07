<?php

namespace Tests\Unit;

use Webulator\Contracts\RouteCollection;

class RouteCollectionTest extends BaseTest
{
    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * Resolve the RouteCollection out of the container.
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->collection = $this->bootedApp()->make(RouteCollection::class);
    }

    /** @test */
    public function it_can_set_and_retrieve_a_simple_route_using_get()
    {
        $this->collection->get("/test", "TestController@getTest");
        $routes = $this->extractSimpleRoutes($this->collection->retrieve(), "GET");

        $this->assertArrayHasKey("/test", $routes, "The route was not found in the collection.");
        $this->assertEquals("TestController@getTest", $routes["/test"]);
    }

    /** @test */
    public function it_can_set_and_retrieve_a_simple_route_using_post()
    {
        $this->collection->post("/test", "TestController@postTest");
        $routes = $this->extractSimpleRoutes($this->collection->retrieve(), "POST");

        $this->assertArrayHasKey("/test", $routes, "The route was not found in the collection.");
        $this->assertEquals("TestController@postTest", $routes["/test"]);
    }

    /** @test */
    public function it_can_set_and_retrieve_a_simple_route_using_add()
    {
        $this->collection->add("PUT", "/test", "TestController@addTest");
        $routes = $this->extractSimpleRoutes($this->collection->retrieve(), "PUT");

        $this->assertArrayHasKey("/test", $routes, "The route was not found in the collection.");
        $this->assertEquals("TestController@addTest", $routes["/test"]);
    }

    /** @test */
    public function it_can_set_and_retrieve_complex_routes_using_all_methods()
    {
        $this->collection->get("/test/{case:\d+}[/optional]", "TestController@complexGetTest");
        $this->collection->post("/test/{case:\d+}[/optional]", "TestController@complexGetTest");
        $this->collection->add("PUT", "/test/{case:\d+}[/optional]", "TestController@complexGetTest");

        $getRoute = $this->extractComplexRoutes($this->collection->retrieve(), "GET");
        $postRoute = $this->extractComplexRoutes($this->collection->retrieve(), "POST");
        $addRoute = $this->extractComplexRoutes($this->collection->retrieve(), "PUT");

        $this->assertArrayHasKey("regex", $getRoute, "The complex route was not compiled for get command.");
        $this->assertArrayHasKey("routeMap", $getRoute, "The complex route was not compiled for get command.");

        $this->assertArrayHasKey("regex", $postRoute, "The complex route was not compiled for post command.");
        $this->assertArrayHasKey("routeMap", $postRoute, "The complex route was not compiled for post command.");

        $this->assertArrayHasKey("regex", $addRoute, "The complex route was not compiled for add command.");
        $this->assertArrayHasKey("routeMap", $addRoute, "The complex route was not compiled for add command.");
    }

    /**
     * Narrow down the array for easier checks.
     *
     * @param array $retrieve
     * @param string $verb
     * @return mixed
     */
    private function extractSimpleRoutes(array $retrieve, string $verb)
    {
        return $retrieve[0][$verb];
    }

    /**
     * Narrow down the array for easier checks.
     *
     * @param array $retrieve
     * @param string $verb
     * @return mixed
     */
    private function extractComplexRoutes(array $retrieve, string $verb)
    {
        return $retrieve[1][$verb][0];
    }
}