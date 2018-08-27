<?php
// Set ROOT path.
define("__ROOT__", realpath(__DIR__.DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR);

// Load the environment variables.
(new \Symfony\Component\Dotenv\Dotenv())->load(__ROOT__.".env");

// Prepare DI Container.
$container = new DI\Container();

// Create the app.
$app = new \Kucasoft\Application($container);

return $app;