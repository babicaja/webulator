<?php

namespace Tests\Feature;

use Webulator\Contracts\Configuration;
use Webulator\Contracts\Response;

class RoutingTest extends FeatureBase
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
        $this->emptyRouteCollection->get("/test", "TestController@test");

        $this->setRoutes($this->emptyRouteCollection);

        $response = $this->post("/test");
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(405, $response->getStatusCode());
    }

    /**
     * @test
     * @throws \Webulator\Exceptions\ContainerResolveException
     * @expectedException \Webulator\Exceptions\ControllerDoesNotExistException
     */
    public function it_can_handle_a_nonexistent_controller_route()
    {
        $this->emptyRouteCollection->get("/test", "TestController@test");

        $this->setRoutes($this->emptyRouteCollection);

        $this->get("/test");
    }

    /**
     * @test
     * @expectedException \Webulator\Exceptions\ControllerActionDoesNotExist
     * @throws \Exception
     */
    public function it_can_handle_a_nonexistent_action_route()
    {
        $configuration = $this->bootedApp()->resolve(Configuration::class);

        $identifier = $configuration->get("controller.namespace") . "TestController";

        $controllerMock = $this->getMockBuilder($identifier)->getMock();

        $this->bootedApp()->bind($identifier, $controllerMock);

        $this->emptyRouteCollection->get("/test", "TestController@test");

        $this->setRoutes($this->emptyRouteCollection);

        $this->get("/test");
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_will_return_a_response_if_all_is_good()
    {
        $configuration = $this->bootedApp()->resolve(Configuration::class);

        $identifier = $configuration->get("controller.namespace") . "TestController";

        $controllerMock = $this->getMockBuilder($identifier)->setMethods(["test"])->getMock();
        $controllerMock->method("test")->willReturn($this->bootedApp()->resolve(Response::class));

        $this->bootedApp()->bind($identifier, $controllerMock);

        $this->emptyRouteCollection->get("/test", "TestController@test");

        $this->setRoutes($this->emptyRouteCollection);

        $response = $this->get("/test");

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}