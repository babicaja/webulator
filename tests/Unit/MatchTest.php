<?php

namespace Tests\Unit;

use Webulator\Contracts\Match;

class MatchTest extends BaseTest
{
    /**
     * @var Match
     */
    private $match;

    /**
     * Resolve the match out of the container.
     *
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->match = $this->bootedApp()->resolve(Match::class);
    }

    /**
     * Reset the match object because we resolve it out of the container and it returns a singleton.
     */
    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDown();

        $this->match->reset();
    }


    /** @test */
    public function it_can_set_and_get_a_valid_status_value()
    {
        $this->match->status($this->match::FOUND);
        $this->assertEquals($this->match::FOUND, $this->match->status());
        // Edge case, a string when cased represents the expected value.
        $this->match->status((string) $this->match::FOUND);
        $this->assertEquals($this->match::FOUND, $this->match->status());
    }

    /**
     * @test
     * @param $status
     * @dataProvider invalidStatus
     */
    public function it_can_only_accept_a_valid_status_value($status)
    {
        $this->match->status($status);
        $this->assertEquals($this->match::NOT_FOUND, $this->match->status());
    }

    /**
     * The invalid status values.
     *
     * @return array
     */
    public function invalidStatus()
    {
        return [
            ["4", "The status can't be a string ot at lest a string that is not cased to allowed integer."],
            [4, "The status can't be a value not defined in interface constants."],
        ];
    }

    /** @test */
    public function it_can_set_and_get_the_controller()
    {
        $this->match->controller("TestController");
        $this->assertEquals("TestController", $this->match->controller());
    }

    /** @test */
    public function it_can_set_and_get_the_action()
    {
        $this->match->action("testAction");
        $this->assertEquals("testAction", $this->match->action());
    }

    /** @test */
    public function it_can_set_and_get_the_parameters()
    {
        $this->match->parameters(["testParameter" => "testValue"]);
        $this->assertArrayHasKey("testParameter", $this->match->parameters());
        $this->assertEquals("testValue", $this->match->parameters()["testParameter"]);
    }
}