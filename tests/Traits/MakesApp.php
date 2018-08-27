<?php

namespace Tests\Traits;

use Kucasoft\Application;

trait MakesApp
{
    private $instance = null;

    /**
     * Returns booted app, which has all the building blocks wired.
     *
     * @return Application
     */
    public function bootedApp()
    {
        if (!$this->instance) {

            return $this->instance = require __DIR__ . "/../../bootstrap/app.php";

        } else {

            return $this->instance;
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