<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * Get Application instance.
     *
     * @return mixed
     */
    protected function bootstrap()
    {
        return require __DIR__ . '/../../bootstrap/app.php';
    }
}