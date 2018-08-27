<?php
// Set ROOT path.
define("__ROOT__", realpath(__DIR__.DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR);

// Load the environment variables.
(new \Symfony\Component\Dotenv\Dotenv())->load(__ROOT__.".env");

// Create the application.
$app = new \Webulator\Application();

// Bind key components to application.
$app->bind(\Webulator\Contracts\Request::class, DI\create(\Webulator\HTTP\Request::class)->constructor($_SERVER));
$app->bind(\Webulator\Contracts\Response::class, DI\create(\Webulator\HTTP\Response::class));
$app->bind(\Webulator\Contracts\RequestHandler::class, DI\autowire(\Webulator\HTTP\RequestHandler::class));
$app->bind(\Webulator\Contracts\MiddlewareHandler::class, DI\autowire(\Webulator\Middleware\MiddlewareHandler::class));

// Load middleware
$app->pipe([
    \Webulator\Middleware\Example::class,
]);

return $app;