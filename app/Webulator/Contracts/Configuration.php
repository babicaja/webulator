<?php

namespace Webulator\Contracts;

interface Configuration
{
    /**
     * Sets the value for the given key.
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);

    /**
     * Returns the value for the given key, or optionally the default value if no key is present.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);
}