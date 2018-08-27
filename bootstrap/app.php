<?php
// Set ROOT path.
define("__ROOT__", realpath(__DIR__.DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR);

// Load the environment variables.
(new \Symfony\Component\Dotenv\Dotenv())->load(__ROOT__.".env");

// Create the application.
$app = new \Kucasoft\Application();

// Bind key components to application.
$app->bind(\Kucasoft\Contracts\Request::class, DI\create(\Kucasoft\HTTP\Request::class)->constructor($_SERVER));
$app->bind(\Kucasoft\Contracts\Response::class, DI\create(\Kucasoft\HTTP\Response::class));
$app->bind(\Kucasoft\Contracts\RequestHandler::class, DI\autowire(\Kucasoft\HTTP\RequestHandler::class));
$app->bind(\Kucasoft\Contracts\MiddlewareHandler::class, DI\autowire(\Kucasoft\Middleware\MiddlewareHandler::class));

// Load middleware
$app->pipe([
    \Kucasoft\Middleware\Example::class,
]);

return $app;