<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Database\SQL;

use Opis\Database\Connection;

class Select extends SelectStatement
{
    protected $connection;
    
    public function __construct(Connection $connection, Compiler $compiler, $tables, $joins, WhereClause $clause = null)
    {
        parent::__construct($compiler, $tables, $clause);
        $this->connection = $connection;
        $this->joins = $joins;
    }
    
    public function into($table, $database = null)
    {
        $this->intoTable = $table;
        $this->intoDatabase = $database;
        return $this;
    }
    
    public function select($columns = array())
    {
        parent::select($columns);
        return $this->connection->query((string) $this, $this->compiler->getParams());
    }
    
    public function column($name)
    {
        parent::column($name);
        return $this->connection->column((string) $this, $this->compiler->getParams());
    }
    
    public function count($column = '*',  $distinct = false)
    {
        parent::count($column, $distinct);
        return $this->connection->column((string) $this, $this->compiler->getParams());
    }
    
    public function avg($column, $distinct = false)
    {
        parent::avg($column, $distinct);
        return $this->connection->column((string) $this, $this->compiler->getParams());
    }
    
    public function sum($column, $distinct  = false)
    {
        parent::sum($column, $distinct);
        return $this->connection->column((string) $this, $this->compiler->getParams());
    }
    
    public function min($column, $distinct = false)
    {
        parent::min($column, $distinct);
        return $this->connection->column((string) $this, $this->compiler->getParams());
    }
    
    public function max($column, $distinct = false)
    {
        parent::max($column, $distinct);
        return $this->connection->column((string) $this, $this->compiler->getParams());
    }
    
}
