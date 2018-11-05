<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Traits\MakesApp;
use Webulator\Contracts\Request;
use Webulator\Contracts\RequestHandler;
use Webulator\Contracts\RouteCollection;
use Zend\Diactoros\Uri;

class FeatureBase extends TestCase
{
    use MakesApp;

    /**
     * @var RouteCollection
     */
    private static $collection;

    /**
     * @throws \Exception
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $indexAsList = file(rootPath("public/index.php"));

        $routeLines = array_filter($indexAsList, function($value)
        {
            return preg_match("/app->routes\(\)/", trim($value));
        });

        $commands = array_map(function($value)
        {
            return explode("->", $value)[2];

        }, $routeLines);

        self::$collection = (new FeatureBase)->bootedApp()->make(RouteCollection::class);

        foreach ($commands as $command)
        {
            $data = explode("(", $command);
            $action = $data[0];

            $parameters = substr(substr(rtrim($data[1]), 0, -3), 1);
            $parameters = preg_replace("/\"/", "", $parameters);
            $parameters = explode(",", $parameters);

            call_user_func_array([self::$collection, $action], $parameters);

            (new FeatureBase)->bootedApp()->bind(RouteCollection::class, self::$collection);
        }
    }

    public function get($route)
    {
        /** @var RequestHandler $handler */
        $handler = $this->bootedApp()->make(RequestHandler::class);
        /** @var Request $request */
        $request = $this->bootedApp()->resolve(Request::class);
        return $handler->handle($request->withUri(new Uri($route)));
    }
}