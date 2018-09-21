<?php

// Here you can create any helper functions that will be accessible through out the application.

if (!function_exists("rootPath"))
{
    /**
     * Returns the root path of the application with an optional appended file or directory.
     *
     * @param string $append
     * @return string
     */
    function rootPath(string $append = "") : string
    {
        $append = ltrim($append, "/");

        $path = implode(DIRECTORY_SEPARATOR, [
            __DIR__, "..", "..", $append
        ]);

        return realpath($path) ? : "";
    }
}