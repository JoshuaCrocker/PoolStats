<?php
/**
 * Created by PhpStorm.
 * User: joshuacrocker
 * Date: 10/02/2018
 * Time: 17:11
 */

namespace App\Support\Database;


trait CacheQueryBuilder
{
    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new Builder($conn, $grammar, $conn->getPostProcessor());
    }
}