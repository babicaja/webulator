<?php

namespace Kucasoft\HTTP;

use Kucasoft\Contracts\RequestHandler as WebulatorRequestHandler;
use Kucasoft\Contracts\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestHandler implements WebulatorRequestHandler
{
    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->response->getBody()->write("Handled by request handler.");
        return $this->response;
    }
}