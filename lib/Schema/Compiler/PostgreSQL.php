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

namespace Opis\Database\Schema\Compiler;

use Opis\Database\Schema\Compiler;
use Opis\Database\Schema\BaseColumn;
use Opis\Database\Schema\AlterTable;

class PostgreSQL extends Compiler
{
    
    protected $modifiers = array('nullable', 'default');
    
    protected $intTypes = array(
        'normal' => 'INTEGER',
        'tiny' => 'SMALLINT',
        'small' => 'SMALLINT',
        'medium' => 'INTEGER',
        'big' => 'BIGINT',
    );
    
    protected function handleTypeInteger(BaseColumn $column)
    {
        if($column->get('autoincrement', false))
        {
            if($column->get('size', 'normal') === 'big')
            {
                return 'BIGSERIAL';
            }
            
            return 'SERIAL';
        }
        
        return $this->intTypes[$column->get('size', 'normal')];
    }
    
    protected function handleIndexKeys(CreateTable $schema)
    {
        $indexes = $schema->getIndexes();
        
        if(empty($indexes))
        {
            return array();
        }
        
        $sql = array();
        
        $table = $schema->getTableName();
        
        foreach($indexes as $name => $columns)
        {
            $sql[] = 'CREATE INDEX ' . $this->wrap($table . '_' . $name) . ' ON ' . $this->wrap($table) . '(' . $this->wrapArray($columns) . ')';
        }
        
        return $sql;
    }
    
    protected function handleAddIndex(AlterTable $table, $data)
    {
        return 'CREATE INDEX ' . $this->wrap($table->getTableName() . '_' . $data['name']) . ' ON ' . $this->wrap($table->getTableName()) . ' ('. $this->wrapArray($data['columns']) . ')';
    }
    
    protected function handleDropIndex(AlterTable $table, $data)
    {
        return 'DROP INDEX ' . $this->wrap($table->getTableName() . '_' . $data);
    }
}
