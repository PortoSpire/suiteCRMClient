<?php

/**
 * Description of Filter
 * 
 * PHP version 7
 * 
 * * * License * * * 
 * Copyright (C) 2022 PortoSpire, LLC.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 * * * End License * * * 
 * 
 * @category  Model
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   GIT: 0.0.6
 * @link      https://portospire.github.io/ 
 */

namespace PortoSpire\SuiteCRMClient\Model;

/**
 * Description of Filter
 *
 * @category  Model
 * @package   SuiteCRMClient
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2022 PORTOSPIRE
 * @license   LGPL 3
 * @version   Release: 0.0.6
 * @link      https://portospire.github.io/
 * @since     Class available since Release 0.0.0
 */
class Filter
{
    const _operators = ['and'=>'&','or'=>'||'],
        _conditions = ['EQ'=>'=','NEQ'=>'!=','GT'=>'>','GTE'=>'>=','LT'=>'<','LTE'=>'<='];
    public $operator,$field,$value,$condition;
    //private $chainedFilterArray;
    //private $chainedFilterString;
    
    public function __construct(array $definition=[],string $condition='EQ',string $operator='and')
    {
        if($definition !== []){
            $this->condition = $this->checkCondition($condition);
            $this->operator = $this->checkOperator($operator);
            $this->field = key($definition);
            $this->value = $definition[$this->field];
        }
    }
    /*
    private function buildChainedFilter($operator='and')
    {
        $this->buildChainedArray($operator);
        $this->buildChainedString($operator);
    }
    
    private function buildChainedString($operator='and')
    {
        $this->chainedFilterString = $this->chainedFilterString.'&'.$this->toString();
    }
    
    private function buildChainedArray($operator='and')
    {
        $this->chainedFilterArray[] = $this->toArray();
    }
     * */
    
    public function and(Filter $filter)
    {
        
    }
    
    public function or(Filter $filter)
    {
        
    }
    
    public function export($type='array'){
        $method = 'to'. ucfirst($type);
        if(method_exists($this, $method)){
            return $this->$method;
        }
    }
    
    public function toString()
    {
        $condition = $this->checkCondition($this->condition);
        $operator = $this->checkOperator($this->operator);
        return 'filter[operator]='.$operator.'&filter['.$this->field.']['.$condition.']='.$this->value;
    }
    
    public function toArray()
    {
        $condition = $this->checkCondition($this->condition);
        $operator = $this->checkOperator($this->operator);
        return ['filter[operator]'=>$operator,'filter['.$this->field.']['.$condition.']'=>$this->value];
    }
    
    public function __toString()
    {
        return $this->toString();
    }
    
    private function checkOperator($operator)
    {
        if(array_key_exists($operator, $this::_operators)){
            return $operator;
        }
        if($key = array_search($operator, $this::_operators)){
            return $key;
        }
        return 'and'; // default to and
    }
    
    private function checkCondition($condition)
    {
        if(array_key_exists($condition, $this::_conditions)){
            return $condition;
        }
        if($key = array_search($condition, $this::_conditions)){
            return $key;
        }
        return 'EQ'; //default to equals
    }
}
