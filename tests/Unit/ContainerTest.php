<?php

namespace Tests\Unit;

use Tests\Traits\MakesApp;

class ContainerTest extends BaseTest
{
    use MakesApp;

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_bind_and_resolve_a_simple_value()
    {
        $app = $this->app();
        $app->bind("Tester", "Working");

        $this->assertEquals("Working", $app->resolve("Tester"), "The container could not bind a simple value");
    }

    /**
     * @test
     * @expectedException \Kucasoft\Exceptions\ContainerResolveException
     * @throws \Exception
     */
    public function it_handles_a_container_exception_by_throwing_a_wrapper_exception()
    {
        $app = $this->app();
        $app->resolve("Nothing");
    }
}