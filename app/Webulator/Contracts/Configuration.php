<?php

namespace Webulator\Contracts;

interface Configuration
{
    public static function load($path);
    public function set($key, $value);
    public function get($key, $default = null);
}