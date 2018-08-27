<?php
// Set ROOT path
define("__ROOT__", realpath(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR));

// Load the environment variables.
(new \Symfony\Component\Dotenv\Dotenv())->load(__ROOT__.DIRECTORY_SEPARATOR.".env");

return new \Kucasoft\Application();