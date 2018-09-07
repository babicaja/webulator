<?php

namespace Tests\Unit;

use Webulator\Contracts\Request;
use Webulator\HTTP\RequestFactory;

class RequestFactoryTest extends BaseTest
{
    /** @test */
    public function it_can_create_a_proper_request()
    {
        $this->assertInstanceOf(Request::class, RequestFactory::createFromGlobals());
    }

    /** @test */
    public function it_can_create_a_proper_request_using_zend_factory_parent_call()
    {
        $this->assertInstanceOf(Request::class, RequestFactory::fromGlobals());
    }

    /** @test */
    public function it_can_create_a_request_with_server_override()
    {
        $request = RequestFactory::createFromGlobals(["foo" => "bar"]);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertArraySubset(["foo" => "bar"], $request->getServerParams());
    }

    /** @test */
    public function it_can_create_a_request_with_query_override()
    {
        $request = RequestFactory::createFromGlobals(null, ["foo" => "bar"]);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertArraySubset(["foo" => "bar"], $request->getQueryParams());
    }

    /** @test */
    public function it_can_create_a_request_with_body_override()
    {
        $request = RequestFactory::createFromGlobals(null, null, ["foo" => "bar"]);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertArraySubset(["foo" => "bar"], $request->getParsedBody());
    }

    /** @test */
    public function it_can_create_a_request_with_cookie_override()
    {
        $request = RequestFactory::createFromGlobals(null, null, null, ["foo" => "bar"]);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertArraySubset(["foo" => "bar"], $request->getCookieParams());
    }

    /** @test */
    public function it_can_create_a_request_with_file_override()
    {
        $request = RequestFactory::createFromGlobals(null, null, null, null, ["foo" => ["tmp_name" => "temp", "size" => 50, "error" => UPLOAD_ERR_OK]]);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertArrayHasKey("foo", $request->getUploadedFiles());
    }
}