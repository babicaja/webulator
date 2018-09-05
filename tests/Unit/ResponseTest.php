<?php

namespace Tests\Unit;

use Tests\Traits\TestsHttpMessages;
use Webulator\Contracts\Response;

class ResponseTest extends BaseTest
{
    use TestsHttpMessages;

    /**
     * @var Response
     */
    private $response;

    /**
     * Resolve the response out of the container.
     *
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->response = $this->bootedApp()->resolve(Response::class);
        $this->message($this->response);
    }

    /** @test */
    public function it_can_set_end_get_the_status_code_with_reason_phrase()
    {
        $response = $this->response->withStatus(500, "test");
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("test", $response->getReasonPhrase());
    }
}