<?php

namespace Webulator\Router;

use Webulator\Contracts\Match as WebulatorMatch;

class Match implements WebulatorMatch
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Match constructor.
     */
    public function __construct()
    {
        $this->status = self::NOT_FOUND;
        $this->controller = '';
        $this->action = '';
        $this->parameters = [];
    }

    /**
     * Returns the status or sets the status if $status parameter is passed.
     *
     * @param int $status
     * @return int
     */
    public function status(int $status = 0)
    {
        if ($status) {
            $this->status = $status;
        }

        return $this->status;
    }

    /**
     * Returns the controller or sets the controller if $controller parameter is passed.
     *
     * @param string $controller
     * @return string
     */
    public function controller(string $controller = '') : string
    {
        if (!empty($controller)) {

            $this->controller = $controller;
        }

        return $this->controller;
    }

    /**
     * Returns the action or sets the action if $action parameter is passed.
     *
     * @param string $action
     * @return string
     */
    public function action(string $action = '') : string
    {
        if (!empty($action)) {

            $this->action = $action;
        }

        return $this->action;
    }

    /**
     * Returns the parameters or sets parameters if the $parameters parameter is passed.
     *
     * @param array $parameters
     * @return array
     */
    public function parameters(array $parameters = []): array
    {
        if (count($parameters) > 0) {
            $this->parameters = $parameters;
        }

        return $this->parameters;
    }
}