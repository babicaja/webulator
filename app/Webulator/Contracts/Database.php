<?php

namespace Webulator\Contracts;

use Pixie\QueryBuilder\QueryBuilderHandler;

interface Database
{
    /**
     * Sets the appropriate table for manipulation.
     *
     * @param $table string|array
     * @return QueryBuilderHandler
     */
    public function table($table);

    /**
     * Pass in a raw sql query, no questions asked.
     *
     * @param $sql
     * @param array $bindings
     * @return mixed
     */
    public function query($sql, $bindings = []);
}