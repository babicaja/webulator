<?php

namespace Tests\Unit;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Tests\Traits\TestsHttpMessages;
use Webulator\Contracts\Request;
use Zend\Diactoros\PhpInputStream;
use Zend\Diactoros\UploadedFile;
use Zend\Diactoros\Uri;

class RequestTest extends BaseTest
{
    use TestsHttpMessages;

    /**
     * @var Request
     */
    private $request;

    /**
     * Resolve the request out of the container.
     *
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->request = $this->bootedApp()->resolve(Request::class);
        $this->message($this->request);
    }


    /** @test */
    public function it_can_set_and_get_the_request_target()
    {
        $request = $this->request->withRequestTarget("www.example.com");
        $target = $request->getRequestTarget();

        $this->assertEquals("www.example.com", $target);
    }

    /** @test */
    public function it_can_set_and_get_the_method()
    {
        $request = $this->request->withMethod("PUT");
        $method = $request->getMethod();

        $this->assertEquals("PUT", $method);
    }

    /** @test */
    public function it_can_set_and_get_the_uri()
    {
        $uri = new Uri("/test");
        $request = $this->request->withUri($uri);

        $this->assertInstanceOf(UriInterface::class, $request->getUri(), "The URI can't be set.");
    }

    /** @test */
    public function it_can_get_the_server_params()
    {
        $params = $this->request->getServerParams();

        $this->assertEquals("array", gettype($params));
        $this->assertTrue(count($params) > 0);
    }

    /** @test */
    public function it_can_set_and_get_cookies()
    {
        $request = $this->request->withCookieParams(["test" => "cookie"]);
        $this->assertEquals(["test" => "cookie"], $request->getCookieParams(), "The cookies could not be set.");
    }

    /** @test */
    public function it_can_set_and_get_the_query_string()
    {
        $request = $this->request->withQueryParams(["test" => "query"]);
        $this->assertEquals(["test" => "query"], $request->getQueryParams(), "The query sting could not be set.");
    }

    /** @test */
    public function it_can_set_and_get_an_uploaded_file()
    {
        $file = new UploadedFile(new PhpInputStream(),50, UPLOAD_ERR_OK);
        $request = $this->request->withUploadedFiles([$file]);

        $this->assertEquals($file, $request->getUploadedFiles()[0], "The upload file could not be set.");
    }

    /** @test */
    public function it_can_set_and_get_the_parsed_body()
    {
        $request = $this->request->withParsedBody(["test" => "post"]);
        $this->assertEquals(["test" => "post"], $request->getParsedBody(), "The parsed body could not be set.");
    }

    /** @test */
    public function it_can_set_and_get_attributes()
    {
        $request = $this->request->withAttribute("attr_one", "one");
        $request = $request->withAttribute("attr_two","two");

        $attributes = $request->getAttributes();

        $this->assertArrayHasKey("attr_one", $attributes, "The attribute was not set.");
        $this->assertArrayHasKey("attr_two", $attributes, "The attribute was not set.");

        $this->assertEquals("one", $attributes["attr_one"], "The attribute does not have the right value.");
        $this->assertEquals("two", $attributes["attr_two"], "The attribute does not have the right value.");
    }

    /** @test */
    public function it_can_get_a_specific_attribute()
    {
        $request = $this->request->withAttribute("attr_one", "one");
        $attribute = $request->getAttribute("attr_one");

        $this->assertEquals("one", $attribute, "The attribute does not have the right value.");

        $attribute = $request->getAttribute("does_not_exist", "default_value");
        $this->assertEquals("default_value", $attribute, "The default value is not returned.");
    }

    /** @test */
    public function it_can_unset_an_attribute()
    {
        $request = $this->request->withAttribute("attr_one", "one");
        $request = $request->withoutAttribute("attr_one");

        $attribute = $request->getAttribute("attr_one", "default_value");
        $this->assertEquals("default_value", $attribute, "The default value is not returned.");
    }
}