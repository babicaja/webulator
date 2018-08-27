<?php

namespace Tests\Unit;

use Kucasoft\Application;

class CoreTest extends BaseTest
{
    /** @test */
    public function it_can_bootstrap_the_application()
    {
        $app = $this->bootstrap();
        $this->assertInstanceOf(Application::class, $app, "The application could not be bootstrapped.");
    }
}