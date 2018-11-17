<?php

namespace Tests\Unit;

use Webulator\Contracts\Configuration;

class HelpersTest extends UnitBase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Resolve the Configuration component out od the container.
     *
     * @throws \Exception
     */
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = $this->bootedApp()->make(Configuration::class);
    }

    /** @test **/
    public function it_can_append_a_path_to_root()
    {
        $this->assertStringEndsWith("public", rootPath("public"));
    }

    /** @test **/
    public function it_will_return_a_path_to_storage_if_name_contains_sqlite()
    {
        $storagePath = $this->configuration->get("storage.path");
        $sqliteName = "test.sqlite";

        $this->assertEquals($storagePath.DIRECTORY_SEPARATOR.$sqliteName, sqliteName($sqliteName));
    }

    /** @test **/
    public function it_will_point_to_public_assets_folder()
    {
        $asset = "some.js";
        $this->assertEquals(DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR.$asset, asset($asset));
    }
}












