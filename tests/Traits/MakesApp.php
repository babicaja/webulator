<?php

namespace Tests\Traits;

use Webulator\Application;

trait MakesApp
{
    private static $instance = null;

    /**
     * Returns booted app, which has all the building blocks wired.
     *
     * @return Application
     */
    public function bootedApp()
    {
        if (!self::$instance) {

            self::$instance = require __DIR__ . "/../../bootstrap/app.php";
            return self::$instance;

        } else {

            return self::$instance;
        }
    }

    /**
     * Returns empty application shell.
     *
     * @return Application
     */
    public function app()
    {
        return new Application();
    }
}