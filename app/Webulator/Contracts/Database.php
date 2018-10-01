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
}