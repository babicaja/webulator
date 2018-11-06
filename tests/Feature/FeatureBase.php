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
     * @var RequestHandler
     */
    private $handler;

    /**
     * @var Request
     */
    public $request;

    /**
     * @throws \Exception
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        (new FeatureBase)->bootedApp()->bind(RouteCollection::class, self::getRouteCollection());
    }

    /**
     * Prepares a RouteCollection object based on scraped data from index.php.
     *
     * @throws \Webulator\Exceptions\ContainerResolveException
     */
    private static function getRouteCollection()
    {
        $collection = (new FeatureBase)->bootedApp()->make(RouteCollection::class);

        $routeDefinitions = self::loadRouteDefinitionsFromIndex();

        foreach ($routeDefinitions as $definition)
        {
            $action = self::extractAction($definition);
            $route = self::extractRoute($definition);
            $handler = self::extractHandler($definition);

            call_user_func_array([$collection, $action], [$route, $handler]);
        }

        return $collection;
    }

    /**
     * Loads the index.php and looks fro $app->route() lines.
     *
     * @return array
     */
    private static function loadRouteDefinitionsFromIndex()
    {
        // Load index.php because it is the recommended place to load the routes.
        $indexAsList = file(rootPath("public/index.php"));
        // Filter down to the $app->routes() line, the default API for defining routes.
        $onlyRouteLines = array_filter($indexAsList, function($value)
        {
            return preg_match("/app->routes\(\)/", trim($value));
        });
        // Remove whitespaces and reset keys
        return array_values(array_map(function ($value)
        {
            return trim($value);
        }, $onlyRouteLines));
    }

    /**
     * Extracts the action e.g. GET from $app->routes()->get("/", Controller-AT-action).
     *
     * @param string $definition
     * @return string
     */
    private static function extractAction(string $definition)
    {
        return trim(explode("(", explode("->", $definition)[2])[0]);
    }

    /**
     * Extracts the route e.g. "/" from $app->routes()->get("/", Controller-AT-action).
     *
     * @param string $definition
     * @return bool|string
     */
    private static function extractRoute(string $definition)
    {
        $insideBracket = self::getRouteDefinitionArguments($definition);

        return substr(trim(explode(",", $insideBracket)[0]), 2, -1);
    }

    /**
     * Extracts the handler e.g. Controller-AT-action from $app->routes()->get("/", Controller-AT-action).
     *
     * @param string $definition
     * @return bool|string
     */
    private static function extractHandler(string $definition)
    {
        $insideBracket = self::getRouteDefinitionArguments($definition);

        return substr(trim(explode(",", $insideBracket)[1]),1, -2);
    }

    /**
     * Helper function to extract the arguments out of the $app->routes()->get("/", Controller-AT-action).
     *
     * @param string $definition
     * @return mixed
     */
    private static function getRouteDefinitionArguments(string $definition)
    {
        preg_match_all("/\(([^\]]*)\)/", explode("->", $definition)[2], $arguments);

        return $arguments[0][0];
    }

    /**
     * @throws \Webulator\Exceptions\ContainerResolveException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->handler = $this->bootedApp()->make(RequestHandler::class);
        $this->request = $this->bootedApp()->make(Request::class);
    }

    /**
     * Make a GET request to the underlying application.
     *
     * @param string $route
     * @return \Webulator\Contracts\Response
     */
    public function get(string $route)
    {
        return $this->request("GET", $route);
    }

    /**
     * Make a POST request to the underlying application.
     *
     * @param string $route
     * @param array $payload
     * @return \Webulator\Contracts\Response
     */
    public function post(string $route, array $payload = array())
    {
        return $this->request("POST", $route, $payload);
    }

    /**
     * Make a PUT request to the underlying application.
     *
     * @param string $route
     * @param array $payload
     * @return \Webulator\Contracts\Response
     */
    public function put(string $route, array $payload = array())
    {
        return $this->request("PUT", $route, $payload);
    }

    /**
     * Make a DELETE request to the underlying application.
     *
     * @param string $route
     * @return \Webulator\Contracts\Response
     */
    public function delete(string $route)
    {
        return $this->request("DELETE", $route);
    }

    /**
     * Make a JSON request to the underlying application.
     *
     * @param string $method
     * @param string $route
     * @param array $payload
     * @return \Webulator\Contracts\Response
     */
    public function json(string $method, string $route, array $payload = [])
    {
        $this->request = $this->request->withAddedHeader("Content-Type", "application/json");
        return $this->request($method, $route, $payload);
    }

    /**
     * Make any request to the underlying application.
     *
     * @param string $method
     * @param string $route
     * @param array $payload
     * @return \Webulator\Contracts\Response
     */
    public function request(string $method, string $route, array $payload = [])
    {
        return $this->handler->handle($this->request->withUri(new Uri($route))->withMethod($method)->withParsedBody($payload));
    }

    /**
     * Get defined routes.
     */
    public function getRoutes()
    {
        /** @var RouteCollection $collection */
        $collection = $this->bootedApp()->resolve(RouteCollection::class);

        return $collection->retrieve();
    }
}