<?php

namespace Tests\Unit;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use Webulator\Contracts\Configuration;
use Webulator\Contracts\Logger;

class LoggerTest extends BaseTest
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var Configuration
     */
    private $originalConfig;

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @throws \Exception
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->root = vfsStream::setup("root", 777, [
            "storage" => [
                    "logs" => [
                        "webulator.log"
                    ]
            ]
        ]);

        // We will need the config to change the storage path
        $this->config = $this->bootedApp()->resolve(Configuration::class);
        $this->config->set("storage.path", $this->root->url()."/storage");
        $this->logger = $this->bootedApp()->make(Logger::class);

        $this->originalConfig = $this->config;
    }

    /**
     * Restore original configuration values.
     */
    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDown();

        $this->config = $this->originalConfig;
    }

    /**
     * @test
     * @dataProvider logLevelsProvider
     * @param $level
     */
    public function it_can_log_an_emergency($level)
    {
        $message = "test-${level}-level";
        $method = strtolower($level);
        $logKey = strtoupper($level);

        $this->logger->$method($message);

        /** @var vfsStreamFile $log */
        $log = $this->root->getChild("storage/logs/webulator.log");
        $record = $log->getContent();

        $this->assertContains($message, $record);
        $this->assertContains("webulator.${logKey}", $record);
    }

    /**
     * Provides all the logger message levels.
     */
    public function logLevelsProvider()
    {
        return [
            ["emergency"],
            ["alert"],
            ["critical"],
            ["error"],
            ["warning"],
            ["notice"],
            ["info"],
            ["debug"],
        ];
    }
}