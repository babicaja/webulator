<?php

namespace Tests\Unit;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Webulator\Contracts\Request;
use Zend\Diactoros\PhpInputStream;
use Zend\Diactoros\UploadedFile;
use Zend\Diactoros\Uri;

class RequestTest extends BaseTest
{
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
    }

    /** @test */
    public function it_can_set_and_get_the_protocol_version()
    {
        $request = $this->request->withProtocolVersion("1.0");
        $this->assertEquals("1.0", $request->getProtocolVersion(), "The protocol version can't be set.");

        $request = $this->request->withProtocolVersion("1.1");
        $this->assertEquals("1.1", $request->getProtocolVersion(), "The protocol version can't be set.");
    }

    /**
     * @test
     * @dataProvider invalidProtocolVersions
     * @expectedException \InvalidArgumentException
     * @param $version
     */
    public function it_can_only_set_a_proper_protocol_version($version)
    {
        $this->request->withProtocolVersion($version);
    }

    /**
     * Data provider for the invalid protocol version tests.
     *
     * @return array
     */
    public function invalidProtocolVersions()
    {
        return [
            [1, "Version can't be a number"],
            [1.0, "Version can't be a double"],
            ["1", "Version must be a either 1.0 or 1.1 and not other strings"],
            ["", "Version can't be an empty string"],
            [null, "Version can't be null"],
        ];
    }

    /** @test */
    public function it_can_get_the_headers()
    {
        $request = $this->request->withHeader("x-header-one", "one");
        $request = $request->withHeader("x-header-two", "two");

        $headers = $request->getHeaders();

        $this->assertArrayHasKey("x-header-one", $headers, "The header x-header-one was not set.");
        $this->assertArrayHasKey("x-header-two", $headers, "The header x-header-two was not set.");

        $this->assertEquals("one", $headers["x-header-one"][0], "The header x-header-one does not have the right value.");
        $this->assertEquals("two", $headers["x-header-two"][0], "The header x-header-two does not have the right value.");
    }

    /** @test */
    public function it_can_check_if_a_header_exists()
    {
        $request = $this->request->withHeader("x-header-one", "one");
        $this->assertTrue($request->hasHeader("x-header-one"), "The header x-header-one does not exist.");
    }

    /** @test */
    public function it_can_return_a_specific_header()
    {
        $request = $this->request->withHeader("x-header-one", "one");
        $this->assertEquals(["one"], $request->getHeader("x-header-one"), "The specific header does not exist.");
        $this->assertEquals([], $request->getHeader("x-header-two"), "If there header is not set an empty array is returned.");
    }

    /** @test */
    public function it_can_return_a_header_line()
    {
        $request = $this->request->withHeader("x-header-one", [1,2]);
        $request = $request->withAddedHeader("x-header-one", [3,4]);

        $headerLine = $request->getHeaderLine("x-header-one");

        $this->assertEquals("1,2,3,4", $headerLine, "The header line is not as expected.");

        $headerLine = $request->getHeaderLine("x-header-two");
        $this->assertEquals("", $headerLine, "If the header is not set an empty string is returned.");
    }

    /** @test */
    public function it_can_unset_a_header()
    {
        $request = $this->request->withHeader("x-header-one", "one");
        $request = $request->withoutHeader("x-header-one");

        $this->assertFalse($request->hasHeader("x-header-one"));
    }

    /**
     * @test
     * @dataProvider invalidHeaders
     * @expectedException \InvalidArgumentException
     * @param $name
     * @param $value
     */
    public function it_can_only_set_proper_headers($name, $value)
    {
        $this->request->withHeader($name, $value);
    }

    /**
     * Data provider for invalid headers.
     *
     * @return array
     */
    public function invalidHeaders()
    {
        return [
            [1, "value", "Header name can't be a number"],
            ["", "value", "Header name can't be an empty string"],
            [null, "value", "Header name can't be null"],
            ["x-header", null, "Header value can't be null"],
        ];
    }

    /** @test */
    public function it_can_set_and_get_the_body()
    {
        $stream = new PhpInputStream();
        $request = $this->request->withBody($stream);

        $this->assertInstanceOf(StreamInterface::class, $request->getBody(), "The body can't be set.");
        $this->assertEquals($stream, $request->getBody(), "The body can't be set.");
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