<?php

// Here you can create any helper functions that will be accessible through out the application.

if (!function_exists("rootPath"))
{
    /**
     * Returns the root path of the application with an optional appended file or directory.
     *
     * @param string $append
     * @return string|false
     */
    function rootPath(string $append = "")
    {
        $append = ltrim($append, "/");

        return realpath(".".DIRECTORY_SEPARATOR.$append);
    }
}

if (!function_exists("sqliteName"))
{
    /**
     * Checks if the name contains the .sqlite extension and returns a path to that storage.
     *
     * @param string $name
     * @return false|string
     */
    function sqliteName(string $name = '')
    {
        return strpos($name, ".sqlite") ? rootPath("storage").DIRECTORY_SEPARATOR.$name : $name;
    }
}

if (!function_exists("asset"))
{
    /**
     * Points to the public/assets folder.
     *
     * @param string $asset
     * @return string
     */
    function asset(string $asset)
    {
        return DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR.$asset;
    }
}