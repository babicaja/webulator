<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function it_can_return_the_root_path()
    {
        $this->assertNotEmpty(rootPath());
        $this->assertStringEndsWith("kucasoft". DIRECTORY_SEPARATOR ."webulator", rootPath());
    }

    /** @test */
    public function it_can_append_to_a_path()
    {
        $this->assertStringEndsWith("views", rootPath("views"));
    }
}












