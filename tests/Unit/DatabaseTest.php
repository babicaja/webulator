<?php

namespace Tests\Unit;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Webulator\Contracts\Configuration;
use Webulator\Contracts\Database;

class DatabaseTest extends UnitBase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Database
     */
    private $database;

    /**
     * Resolve app components out of the container.
     *
     * @throws \Exception
     */
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = $this->bootedApp()->resolve(Configuration::class);
        $this->database = $this->makeDatabase();
    }

    /**
     * Clear database instance.
     *
     * @throws \Webulator\Exceptions\ContainerResolveException
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->database = null;
        $this->bootedApp()->make(Configuration::class);
    }


    /**
     * @test
     * @throws \Exception
     * @expectedException \Webulator\Exceptions\DatabaseConnectionException
     * @expectedExceptionMessageRegExp /mongo/
     */
    public function it_will_verify_the_driver()
    {
        $this->configuration->set("database.driver", "mongo");

        $this->database = $this->makeDatabase();

        $this->database->table("start");
    }

    /**
     * @test
     * @throws \Exception
     * @expectedException \Webulator\Exceptions\DatabaseConnectionException
     * @expectedExceptionCode 500
     */
    public function it_will_handle_bad_connection_parameters()
    {
        $this->configuration->set("database.driver", "mysql");
        $this->configuration->set("database.host", "bad.host");

        $this->database = $this->makeDatabase();

        $this->database->table("start");
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_can_store_and_retrieve_a_value_from_database()
    {
        /** @var vfsStreamDirectory $root */
        $root = vfsStream::setup("root", 777, ["storage" => []]);

        $this->configuration->set("storage.path", $root->url().DIRECTORY_SEPARATOR."storage");

        $this->configuration->set("database.driver", "sqlite");
        $this->configuration->set("database.name", "test.sqlite");

        $this->database = $this->makeDatabase();

        $this->database->query("CREATE TABLE test (app VARCHAR(20), vendor VARCHAR(20))")->get();
        $this->database->query("INSERT INTO test (app, vendor) VALUES ('Webulator','Kucasoft')")->get();

        $record = $this->database->table("test")->first();

        $this->assertEquals("Kucasoft", $record->vendor);
        $this->assertEquals("Webulator", $record->app);
    }

    /**
     * Make a new Database instance.
     *
     * @return Database
     * @throws \Exception
     */
    protected function makeDatabase()
    {
        return $this->bootedApp()->make(Database::class);
    }
}