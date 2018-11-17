<?php

namespace Tests\Unit;

use Webulator\Contracts\ErrorHandler;

class ErrorHandlerTest extends UnitBase
{
    /**
     * Resolve the ErrorHandler component out of the container.
     *
     * @throws \Webulator\Exceptions\ContainerResolveException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->bootedApp()->make(ErrorHandler::class);
    }

    /**
     * @test
     * @expectedException \Webulator\Exceptions\ErrorException
     */
    public function it_will_handle_a_runtime_error()
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $x = 1/0;
    }
}