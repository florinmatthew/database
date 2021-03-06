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

class HavingClause
{
    
    protected $conditions = array();
    
    protected $compiler;
    
    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }
    
    public function addGroupCondition(Closure $callback, $separator)
    {
        $having = new HavingCondition($this->compiler);
        $callback($having);
        
        $this->conditions[] = array(
            'type' => 'havingNested',
            'conditions' => $having->getHavingConditions(),
            'separator' => $separator,
        );
        
    }
    
    public function addCondition($aggregate, $value, $operator, $separator)
    {
        if($value instanceof Closure)
        {
            $expr = new Expression($this->compiler);
            $value($expr);
            $value = $expr;
        }
        
        $this->conditions[] = array(
            'type' => 'havingCondition',
            'aggregate' => $aggregate,
            'value' => $value,
            'operator' => $operator,
            'separator' => $separator,
        );
    }
    
    public function addInCondition($aggregate, $value, $separator, $not)
    {
        
        if($value instanceof Closure)
        {
            $select = new Subquery($this->compiler);
            $value($select);
            $this->conditions[] = array(
                'type' => 'havingInSelect',
                'aggregate' => $aggregate,
                'subquery' => $select,
                'separator' => $separator,
                'not' => $not,
            );
        }
        else
        {
            $this->conditions[] = array(
                'type' => 'havingIn',
                'aggregate' => $aggregate,
                'value' => $value,
                'separator' => $separator,
                'not' => $not,
            );
        }
        
    }
    
    public function addBetweenCondition($aggregate, $value1, $value2, $separator, $not)
    {
        
        $this->conditions[] = array(
            'type' => 'havingBetween',
            'aggregate' => $aggregate,
            'value1' => $value1,
            'value2' => $value2,
            'seperator' => $separator,
            'not' => $not,
        );
        
    }
    
    public function getHavingConditions()
    {
        return $this->conditions;
    }
}
