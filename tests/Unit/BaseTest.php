<?php

namespace Tests\Unit;

use Kucasoft\Application;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /** @var Application */
    private static $app;

    /**
     * Wire the tests.
     */
    public static function setUpBeforeClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUpBeforeClass();
        self::bootstrap();
    }

    /**
     * Get Application instance.
     */
    private static function bootstrap()
    {
        self::$app = require __DIR__ . '/../../bootstrap/app.php';
    }

    /**
     * Get the bootstrapped application.
     *
     * @return Application
     */
    public function app()
    {
        return self::$app;
    }
}