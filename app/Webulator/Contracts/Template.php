<?php

namespace Webulator\Contracts;

interface Template
{
    /**
     * Renders the view with the given parameters.
     * @param string $view
     * @param array $parameters
     * @return mixed
     */
    public function render($view, array $parameters = []);
}