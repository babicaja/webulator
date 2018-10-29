<?php

namespace Tests\Unit;

use Webulator\Contracts\Response;
use Webulator\Contracts\ResponseEmitter;

class ResponseEmitterTest extends BaseTest
{
    /**
     * @var ResponseEmitter
     */
    private $emitter;

    /**
     * @var Response
     */
    private $response;

    /**
     * Resolve components out of the container.
     *
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->emitter = $this->bootedApp()->resolve(ResponseEmitter::class);
        $this->response = $this->bootedApp()->resolve(Response::class);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function it_can_emit_a_response()
    {
        // Make a dummy response
        $this->response->getBody()->write('test-output');
        $this->response = $this->response->withHeader("X-Test", 1);
        $this->response = $this->response->withStatus(202);

        // Emmit it
        $result = $this->emitter->emit($this->response);

        // Get the emitted headers status code and content
        $header = explode(':', xdebug_get_headers()[0])[0];
        $code = http_response_code();
        $content = ob_get_contents();

        $this->assertTrue($result);
        $this->assertEquals("X-Test", $header);
        $this->assertEquals(202, $code);
        $this->assertEquals('test-output', $content);
    }
}