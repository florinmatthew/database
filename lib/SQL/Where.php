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

use Closure;

class Where
{
    protected $condition;
    protected $column;
    protected $separator;
    protected $whereClause;
    
    public function __construct(WhereCondition $condition)
    {
        $this->condition = $condition;
        $this->whereClause = $condition->getWhereClause();
    }
    
    public function init($column, $separator)
    {
        $this->column = $column;
        $this->separator = $separator;
        return $this;
    }
    
    protected function addCondition($value, $operator, $iscolumn = false)
    {
        if($iscolumn && is_string($value))
        {
            $value = function($expr) use ($value){
                $expr->column($value);
            };
        }
        
        $this->whereClause->addCondition($this->column, $value, $operator, $this->separator);
        return $this->condition;
    }
    
    protected function addBetweenCondition($value1, $value2, $not)
    {
        $this->whereClause->addBetweenCondition($this->column, $value1, $value2, $this->separator, $not);
        return $this->condition;
    }
    
    protected function addLikeCondition($pattern, $not)
    {
        $this->whereClause->addLikeCondition($this->column, $pattern, $this->separator, $not);
        return $this->condition;
    }
    
    protected function addInCondition($value, $not)
    {
        $this->whereClause->addInCondition($this->column, $value, $this->separator, $not);
        return $this->condition;
    }
    
    public function addNullCondition($not)
    {
        $this->whereClause->addNullCondition($this->column, $this->separator, $not);
        return $this->condition;
    }
    
    public function is($value, $iscolumn = false)
    {
        return $this->addCondition($value, '=', $iscolumn);
    }
    
    public function isNot($value, $iscolumn = false)
    {
        return $this->addCondition($value, '!=', $iscolumn);
    }
    
    public function lessThan($value, $iscolumn = false)
    {
        return $this->addCondition($value, '<', $iscolumn);
    }
    
    public function greaterThan($value, $iscolumn = false)
    {
        return $this->addCondition($value, '>', $iscolumn);
    }
    
    public function atLeast($value, $iscolumn = false)
    {
        return $this->addCondition($value, '>=', $iscolumn);
    }
    
    public function atMost($value, $iscolumn = false)
    {
        return $this->addCondition($value, '<=', $iscolumn);
    }
    
    public function between($value1, $value2)
    {
        return $this->addBetweenCondition($value1, $value2, false);
    }
    
    public function notBetween($value1, $value2)
    {
        return $this->addBetweenCondition($value1, $value2, true);
    }
    
    public function like($value)
    {
        return $this->addLikeCondition($value, false);
    }
    
    public function notLike($value)
    {
        return $this->addLikeCondition($value, true);
    }
    
    public function in($value)
    {
        return $this->addInCondition($value, false);
    }
    
    public function notIn($value)
    {
        return $this->addInCondition($value, true);
    }
    
    public function isNull()
    {
        return $this->addNullCondition(false);
    }
    
    public function notNull()
    {
        return $this->addNullCondition(true);
    }
    
    //Aliases
    
    public function eq($value, $iscolumn = false)
    {
        return $this->is($value, $iscolumn);
    }
    
    public function ne($value, $iscolumn = false)
    {
        return $this->isNot($value, $iscolumn);
    }
    
    public function lt($value, $iscolumn = false)
    {
        return $this->lessThan($value, $iscolumn);
    }
    
    public function gt($value, $iscolumn = false)
    {
        return $this->greaterThan($value, $iscolumn);
    }
    
    public function gte($value, $iscolumn = false)
    {
        return $this->atLeast($value, $iscolumn);
    }
    
    public function lte($value, $iscolumn = false)
    {
        return $this->atMost($value, $iscolumn);
    }
    
}
