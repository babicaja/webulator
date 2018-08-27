<?php

namespace Kucasoft;

class ExceptionHandler
{
    /**
     * @var \Exception
     */
    private static $exception;

    /**
     * Captures any unhandled exceptions.
     *
     * @param \Exception $exception
     */
    public static function capture(\Exception $exception)
    {
        self::$exception = $exception;

        'XMLHttpRequest' == ($_SERVER['X-Requested-With'] ?? '') ? self::respondWithJson() : self::respondWithHtml();
    }

    /**
     * Respond with general json error.
     */
    private static function respondWithJson()
    {
        header('Content-Type: application/json');
        header("HTTP/1.1 503 Service Unavailable");

        $message = [
            "error" => getenv("DEBUG") ? self::$exception->getMessage() : "server-error",
            "message" => "The application is currently unavailable."
        ];

        echo json_encode($message);
    }

    /**
     * Respond with general html error.
     */
    private static function respondWithHtml()
    {
        header('Content-Type: text/html');
        header("HTTP/1.1 503 Service Unavailable");

        if (getenv("DEBUG") == "true") {

            var_dump(self::$exception);

        } else {

            @readfile(__DIR__ . '/../../views/error.html');

        }
    }
}