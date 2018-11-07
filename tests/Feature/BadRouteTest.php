<?php

namespace Tests\Feature;

use Webulator\Contracts\Response;

class BadRouteTest extends FeatureBase
{
    /**
     * @test
     * @throws \Exception
     */
    public function it_will_respond_with_404_when_a_nonexistent_route_is_requested()
    {
        $this->setRoutes($this->emptyRouteCollection);

        $response = $this->get("/nonexistent");
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_respond_with_405_when_a_bad_method_is_requested()
    {
        $this->emptyRouteCollection->get("/test", "TestHandler@test");

        $this->setRoutes($this->emptyRouteCollection);

        $response = $this->post("/test");
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(405, $response->getStatusCode());
    }
}