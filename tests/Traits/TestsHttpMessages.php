<?php

namespace Tests\Traits;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Webulator\Contracts\Request;
use Webulator\Contracts\Response;
use Zend\Diactoros\PhpInputStream;

trait TestsHttpMessages
{
    /**
     * @var Request|Response
     */
    private $message;
    
    /**
     * Set the message to test against.
     *
     * @param $message Request|Response
     */
    public function message($message)
    {
        $this->message = $message;
    }
    
    /** @test */
    public function it_can_set_and_get_the_protocol_version()
    {
        $message = $this->message->withProtocolVersion("1.0");
        TestCase::assertEquals("1.0", $message->getProtocolVersion(), "The protocol version can't be set.");

        $message = $this->message->withProtocolVersion("1.1");
        TestCase::assertEquals("1.1", $message->getProtocolVersion(), "The protocol version can't be set.");
    }

    /**
     * @test
     * @dataProvider invalidProtocolVersions
     * @expectedException \InvalidArgumentException
     * @param $version
     */
    public function it_can_only_set_a_proper_protocol_version($version)
    {
        $this->message->withProtocolVersion($version);
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
        $message = $this->message->withHeader("x-header-one", "one");
        $message = $message->withHeader("x-header-two", "two");

        $headers = $message->getHeaders();

        TestCase::assertArrayHasKey("x-header-one", $headers, "The header x-header-one was not set.");
        TestCase::assertArrayHasKey("x-header-two", $headers, "The header x-header-two was not set.");

        TestCase::assertEquals("one", $headers["x-header-one"][0], "The header x-header-one does not have the right value.");
        TestCase::assertEquals("two", $headers["x-header-two"][0], "The header x-header-two does not have the right value.");
    }

    /** @test */
    public function it_can_check_if_a_header_exists()
    {
        $message = $this->message->withHeader("x-header-one", "one");
        TestCase::assertTrue($message->hasHeader("x-header-one"), "The header x-header-one does not exist.");
    }

    /** @test */
    public function it_can_return_a_specific_header()
    {
        $message = $this->message->withHeader("x-header-one", "one");
        TestCase::assertEquals(["one"], $message->getHeader("x-header-one"), "The specific header does not exist.");
        TestCase::assertEquals([], $message->getHeader("x-header-two"), "If there header is not set an empty array is returned.");
    }

    /** @test */
    public function it_can_return_a_header_line()
    {
        $message = $this->message->withHeader("x-header-one", [1, 2]);
        $message = $message->withAddedHeader("x-header-one", [3, 4]);

        $headerLine = $message->getHeaderLine("x-header-one");

        TestCase::assertEquals("1,2,3,4", $headerLine, "The header line is not as expected.");

        $headerLine = $message->getHeaderLine("x-header-two");
        TestCase::assertEquals("", $headerLine, "If the header is not set an empty string is returned.");
    }

    /** @test */
    public function it_can_unset_a_header()
    {
        $message = $this->message->withHeader("x-header-one", "one");
        $message = $message->withoutHeader("x-header-one");

        TestCase::assertFalse($message->hasHeader("x-header-one"));
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
        $this->message->withHeader($name, $value);
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
        $message = $this->message->withBody($stream);

        TestCase::assertInstanceOf(StreamInterface::class, $message->getBody(), "The body can't be set.");
        TestCase::assertEquals($stream, $message->getBody(), "The body can't be set.");
    }
}