<?php

namespace Webulator\HTTP;

use Webulator\Contracts\Request;
use Webulator\Contracts\RequestFactory as WebulatorServerRequestFactory;
use Webulator\HTTP\Request as WebulatorRequest;
use Zend\Diactoros\ServerRequestFactory as ZendServerRequestFactory;
use function Zend\Diactoros\marshalHeadersFromSapi;
use function Zend\Diactoros\marshalMethodFromSapi;
use function Zend\Diactoros\marshalProtocolVersionFromSapi;
use function Zend\Diactoros\marshalUriFromSapi;
use function Zend\Diactoros\normalizeServer;
use function Zend\Diactoros\normalizeUploadedFiles;
use function Zend\Diactoros\parseCookieHeader;

class RequestFactory extends ZendServerRequestFactory implements WebulatorServerRequestFactory
{
    /**
     * Captures current PHP environment and request.
     *
     * @param array|null $server
     * @param array|null $query
     * @param array|null $body
     * @param array|null $cookies
     * @param array|null $files
     * @return Request
     */
    public static function createFromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null): Request
    {
        $server = normalizeServer(
            $server ?: $_SERVER,
            is_callable("apache") ? "apache" : null
        );
        $files   = normalizeUploadedFiles($files ?: $_FILES);
        $headers = marshalHeadersFromSapi($server);

        if (null === $cookies && array_key_exists('cookie', $headers)) {
            $cookies = parseCookieHeader($headers['cookie']);
        }

        return new WebulatorRequest(
            $server,
            $files,
            marshalUriFromSapi($server, $headers),
            marshalMethodFromSapi($server),
            'php://input',
            $headers,
            $cookies ?: $_COOKIE,
            $query ?: $_GET,
            $body ?: $_POST,
            marshalProtocolVersionFromSapi($server)
        );
    }

    /**
     * Just to make sure that the parent function does not return the zend request object.
     *
     * @param array|null $server
     * @param array|null $query
     * @param array|null $body
     * @param array|null $cookies
     * @param array|null $files
     * @return Request
     */
    public static function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    )
    {
        return self::createFromGlobals($server, $query, $body, $cookies, $files);
    }
}