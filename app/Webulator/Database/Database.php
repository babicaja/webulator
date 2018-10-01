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
    private $queryBuilder;

    /**
     * Database constructor.
     *
     * @param Configuration $configuration
     * @throws DatabaseConnectionException
     */
    public function __construct(Configuration $configuration)
    {
        $this->verifyDriver($configuration->get("database.driver"));

        try
        {
            $connection = new Connection($this->driver, $configuration->get("database"));
            $this->queryBuilder = new QueryBuilderHandler($connection);
        }
        catch (\Exception $exception)
        {
            throw new DatabaseConnectionException("The connection can't be established.", 500, $exception);
        }

    }

    /**
     * Start building your query with defining a table.
     *
     * @param $table array|string
     * @return QueryBuilderHandler
     */
    public function table($table)
    {
        /** @noinspection PhpParamsInspection */
        return $this->queryBuilder->table($table);
    }

    /**
     * Pass in a raw sql query, no questions asked.
     *
     * @param $sql
     * @param array $bindings
     * @return QueryBuilderHandler
     */
    public function query($sql, $bindings = [])
    {
        return $this->queryBuilder->query($sql, $bindings);
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