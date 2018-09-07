<?php

namespace Webulator\Contracts;

interface Match
{
    const FOUND = 1;
    const NOT_FOUND = 2;
    const NOT_ALLOWED = 3;

    /**
     * Returns the status or sets the status if $status parameter is passed.
     *
     * @param int $status
     * @return int
     */
    public function status(int $status = 0);

    /**
     * Returns the controller or sets the controller if $controller parameter is passed.
     *
     * @param string $controller
     * @return string
     */
    public function controller(string $controller = ''): string;

    /**
     * Returns the action or sets the action if $action parameter is passed.
     *
     * @param string $action
     * @return string
     */
    public function action(string $action = ''): string;

    /**
     * Returns the parameters or sets parameters if the $parameters parameter is passed.
     *
     * @param array $parameters
     * @return array
     */
    public function parameters(array $parameters = []): array;
}