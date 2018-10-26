<?php

namespace Tests\Unit;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Webulator\Contracts\HTTPClient;
use Webulator\Contracts\Request;

class HTTPClientTest extends BaseTest
{
    /**
     * @var HTTPClient
     */
    private $client;

    /**
     * @var Request
     */
    private $request;

    /**
     * Resolve the HTTPClient out of the container.
     *
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->client = $this->bootedApp()->resolve(HTTPClient::class);
        $this->request = $this->bootedApp()->resolve(Request::class);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function it_can_send_a_synchronous_request()
    {
        $request = $this->request->withUri(new Uri("https://httpbin.org/get"));
        $response = $this->client->send($request);

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_can_send_an_asynchronous_request()
    {
        $request = $this->request->withUri(new Uri("https://httpbin.org/get"));
        $response = $this->client->sendAsync($request);

        $response->then(
            function(ResponseInterface $response){
                $this->assertInstanceOf(ResponseInterface::class, $response);
            },
            function(){
                throw new \Exception("The asynchronous request could not be sent.");
            });

        $response->wait();

        $this->assertInstanceOf(PromiseInterface::class, $response);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function it_can_make_a_synchronous_request()
    {
        $response = $this->client->request("GET", "https://httpbin.org/get");
        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_can_make_an_asynchronous_request()
    {
        $response = $this->client->requestAsync("GET", "https://httpbin.org/get");

        $response->then(
            function(ResponseInterface $response){
                $this->assertInstanceOf(ResponseInterface::class, $response);
            },
            function(){
                throw new \Exception("The asynchronous request could not be made.");
            });

        $response->wait();

        $this->assertInstanceOf(PromiseInterface::class, $response);
    }
}