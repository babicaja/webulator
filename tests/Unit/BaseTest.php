<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Traits\MakesApp;

abstract class BaseTest extends TestCase
{
    use MakesApp;
}