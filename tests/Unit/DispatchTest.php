<?php

namespace Tests\Unit;

use Webulator\Contracts\Dispatcher;
use Webulator\Router\Dispatcher as WebulatorDispatcher;

class DispatchTest extends BaseTest
{
    /**
     * @var WebulatorDispatcher
     */
    private $dispatcher;

    /**
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->dispatcher = $this->bootedApp()->resolve(Dispatcher::class);
    }
}