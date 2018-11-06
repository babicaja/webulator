<?php

namespace Tests\Feature;

use Webulator\Contracts\Response;

class BadRouteTest extends FeatureBase
{
    /** @test */
    public function it_will_respond_with_404_when_a_nonexistent_route_is_requested()
    {
        /** @var Response $response */
        $response = $this->get("/nonexistent");
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /** @test */
    public function it_will_respond_with_405_when_a_bad_method_is_requested()
    {
        $routes = $this->getRoutes();

        if (!count($routes[0])) return;

        if (isset($routes[0]["GET"]))
        {
            $route = array_keys($routes[0]["GET"])[0];
            $response = $this->post($route);
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals(405, $response->getStatusCode());

        }
        elseif (isset($routes[0]["POST"]))
        {
            $route = array_keys($routes[0]["POST"])[0];
            $response = $this->post($route);
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals(405, $response->getStatusCode());
        }
    }
}