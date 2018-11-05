<?php

namespace Tests\Feature;

use Webulator\Contracts\Response;

class ExampleTest extends FeatureBase
{
    /** @test */
    public function it_can_see_the_app_version()
    {
        $response = $this->get("/");
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(Response::class, $response);
    }
}