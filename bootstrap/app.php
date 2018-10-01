<?php
// Load the environment variables.
(new \Symfony\Component\Dotenv\Dotenv())->load(rootPath(".env"));

// Create the application.
$app = new \Webulator\Application();

// Prepare DI container.
$container = new \DI\Container();

// Set container manager.
$app->container($container);

// Bind key components to application.
$app->bind(\Webulator\Contracts\Configuration::class, DI\create(\Webulator\Configuration::class));
$app->bind(\Webulator\Contracts\Request::class, DI\factory(function(){ return \Webulator\HTTP\RequestFactory::createFromGlobals();}));
$app->bind(\Webulator\Contracts\Response::class, DI\create(\Webulator\HTTP\Response::class));
$app->bind(\Webulator\Contracts\Dispatcher::class, DI\autowire(\Webulator\Router\Dispatcher::class));
$app->bind(\Webulator\Contracts\Match::class, DI\create(\Webulator\Router\Match::class));
$app->bind(\Webulator\Contracts\RouteCollection::class, DI\create(\Webulator\Router\RouteCollection::class));
$app->bind(\Webulator\Contracts\RequestHandler::class, DI\autowire(\Webulator\Router\RequestHandler::class));
$app->bind(\Webulator\Contracts\MiddlewareHandler::class, DI\autowire(\Webulator\Middleware\MiddlewareHandler::class));
$app->bind(\Webulator\Contracts\Template::class, DI\autowire(Webulator\Template::class));
$app->bind(\Webulator\Contracts\Logger::class, DI\autowire(\Webulator\Utils\Logger::class));
$app->bind(\Webulator\Contracts\Database::class, DI\autowire(\Webulator\Database\Database::class));

// Load middleware.
$app->pipe([
    \Webulator\Middleware\Example::class,
]);

return $app;