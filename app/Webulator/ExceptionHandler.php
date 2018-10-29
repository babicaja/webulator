<?php

namespace Webulator;

class ExceptionHandler
{
    /**
     * @var \Throwable
     */
    private static $throwable;

    /**
     * Captures any unhandled exceptions.
     *
     * @param \Throwable $throwable
     */
    public static function capture(\Throwable $throwable)
    {
        self::$throwable = $throwable;

        'XMLHttpRequest' == ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') ? self::respondWithJson() : self::respondWithHtml();
    }

    /**
     * Respond with general json error.
     */
    private static function respondWithJson()
    {
        header('Content-Type:application/json');
        header("HTTP/1.1 503 Service Unavailable");

        $message = [
            "error" => self::isDebugOn() ? self::$throwable->getMessage() : "server-error",
            "message" => "The application is currently unavailable."
        ];

        echo json_encode($message);
    }

    /**
     * Respond with general html error.
     */
    private static function respondWithHtml()
    {
        header('Content-Type:text/html');
        header("HTTP/1.1 503 Service Unavailable");

        $message = self::isDebugOn() ? self::$throwable->getMessage() : '';

        echo self::htmlTemplate($message);
    }

    /**
     * @return bool
     */
    private static function isDebugOn()
    {
        $debug = getenv("DEBUG");

        if ($debug == "false") $debug = false;

        return (bool) $debug;
    }

    /**
     * Render html message.
     *
     * @param string|null $message
     * @return string
     */
    private static function htmlTemplate(string $message = '')
    {
        return "<div style='text-align:center;width:640px;margin:200px auto'><h2>Sorry, something went horribly wrong.</h2><br>${message}</div>";
    }
}