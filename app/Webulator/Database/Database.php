<?php

namespace Webulator\Database;

use Pixie\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Webulator\Contracts\Configuration;
use Webulator\Contracts\Database as WebulatorDatabase;
use Webulator\Exceptions\DatabaseConnectionException;

class Database implements WebulatorDatabase
{
    /**
     * @var array
     */
    private $drivers = ["mysql", "sqlite"];

    /**
     * @var string
     */
    private $driver;

    /**
     * @var QueryBuilderHandler
     */
    private $handler;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Database constructor.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Start building your query with defining a table.
     *
     * @param $table array|string
     * @return QueryBuilderHandler
     * @throws DatabaseConnectionException
     */
    public function table($table)
    {
        /** @noinspection PhpParamsInspection */
        return $this->handler()->table($table);
    }

    /**
     * Pass in a raw sql query, no questions asked.
     *
     * @param $sql
     * @param array $bindings
     * @return mixed
     * @throws DatabaseConnectionException
     */
    public function query($sql, $bindings = [])
    {
        return $this->handler()->query($sql, $bindings);
    }

    /**
     * Prepares the connection and query builder helper.
     *
     * @throws DatabaseConnectionException
     * @return QueryBuilderHandler
     */
    private function handler()
    {
        if (isset($this->handler)) return $this->handler;

        $this->verifyDriver($this->configuration->get("database.driver"));

        try
        {
            $this->handler = new QueryBuilderHandler(new Connection($this->driver, $this->configuration->get("database")));

            return $this->handler;
        }
        catch (\Exception $exception)
        {
            throw new DatabaseConnectionException("The connection can't be established.", 500, $exception);
        }
    }

    /**
     * Checks if the configuration driver is supported.
     *
     * @param string $driver
     * @throws DatabaseConnectionException
     */
    private function verifyDriver(string $driver)
    {
        if (!in_array($driver, $this->drivers))
        {
            throw new DatabaseConnectionException("The driver ${driver} is not supported.");
        }

        $this->driver = $driver;
    }
}