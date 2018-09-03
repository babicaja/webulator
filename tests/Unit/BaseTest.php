<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Traits\MakesApp;

abstract class BaseTest extends TestCase
{
    use MakesApp;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
    }
}