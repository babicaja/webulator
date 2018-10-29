<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webulator\ExceptionHandler;

class ExceptionHandlerTest extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     */
    public function it_will_show_a_simple_json_error_if_debug_is_off()
    {
        $this->debugOff();
        $this->isAjax();

        ExceptionHandler::capture(new \Exception("test-ajax-debug-off"));

        $this->expectOutputString($this->expectedJSONMessage("server-error"));

        list($contentType, $code, $content) = $this->captureOutput();

        $this->checkJSON($contentType, $code, $content);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function it_will_show_a_detailed_json_error_if_debug_is_on()
    {
        $message = "test-ajax-debug-on";

        $this->debugOn();
        $this->isAjax();

        ExceptionHandler::capture(new \Exception($message));

        $this->expectOutputString($this->expectedJSONMessage($message));

        list($contentType, $code, $content) = $this->captureOutput();

        $this->checkJSON($contentType, $code, $content);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function it_will_show_a_simple_html_error_if_debug_is_off()
    {
        $this->debugOff();
        $this->isNotAjax();

        ExceptionHandler::capture(new \Exception("test-html-debug-off"));

        $this->expectOutputString($this->expectedHTMLMessage());

        list($contentType, $code) = $this->captureOutput();

        $this->checkHTML($contentType, $code);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function it_will_show_a_detailed_html_error_if_debug_is_on()
    {
        $message = "test-html-debug-on";

        $this->debugOn();
        $this->isNotAjax();

        ExceptionHandler::capture(new \Exception($message));

        $this->expectOutputString($this->expectedHTMLMessage($message));

        list($contentType, $code) = $this->captureOutput();

        $this->checkHTML($contentType, $code);
    }

    /**
     * Set the DEBUG env to false
     */
    private function debugOff()
    {
        putenv("DEBUG=false");
    }

    /**
     * Set the DEBUG env to false
     */
    private function debugOn()
    {
        putenv("DEBUG=true");
    }

    /**
     * Simulate an Ajax request.
     */
    private function isAjax()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    }

    /**
     * Make sure it is not an Ajax request.
     */
    private function isNotAjax()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = '';
    }

    /**
     * Check if the response elements meet json requirements.
     *
     * @param $contentType
     * @param $code
     * @param $content
     */
    private function checkJSON($contentType, $code, $content)
    {
        $this->assertEquals('application/json', $contentType);
        $this->assertEquals(503, $code);
        $this->assertJson($content);
    }

    /**
     * Check if the response elements meet html requirements.
     *
     * @param $contentType
     * @param $code
     */
    private function checkHTML($contentType, $code)
    {
        $this->assertEquals('text/html;charset=UTF-8', $contentType);
        $this->assertEquals(503, $code);
    }

    /**
     * Format of the JSON response.
     *
     * @param string $error
     * @return string
     */
    private function expectedJSONMessage(string $error)
    {
        return "{\"error\":\"${error}\",\"message\":\"The application is currently unavailable.\"}";
    }

    /**
     * Format of the HTML message.
     *
     * @param string $message
     * @return string
     */
    private function expectedHTMLMessage(string $message = null)
    {
        return "<div style='text-align:center;width:640px;margin:200px auto'><h2>Sorry, something went horribly wrong.</h2><br>${message}</div>";
    }

    /**
     * Capture the response elements.
     *
     * @return array
     */
    private function captureOutput()
    {
        return [
            trim(explode(':', xdebug_get_headers()[0])[1]),
            http_response_code(),
            ob_get_contents()
        ];
    }
}