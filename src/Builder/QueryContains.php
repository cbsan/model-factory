<?php

/**
 *  @author    Cristian B. Santos <cristian.deveng@gmail.com>
 *  @copyright 2015 Bludata Software.
 *  @license   MIT
 * 
 * Estabele metodos padrões para elaboração de SELECT's personalisados para a model
 */

namespace CBSantos\ModelFactory\Builder;

use \CBSantos\ModelFactory\Builder\QueryCore;

abstract class QueryContains extends QueryCore
{
	/**
	 * Informa os campos que serão visualizados na model
	 * @param  array  $fields [description]
	 * @return [type]         [description]
	 */
	public function select($fields = array('*'))
	{
		$this->fields = $fields;
		
		return $this;
	}

	public function from($table, $alias = 't')
	{
		$this->table = $table;
		$this->alias = $alias;
		
		return $this;
	}

	public function join($joinTable, $aliasJoin, $condition, $type = 'AND')
	{
		return $this->innerJoin($joinTable, $aliasJoin, $condition, $type);
	}


	public function innerJoin($joinTable, $aliasJoin, $condition, $type = 'AND')
	{
		$query = $this->Query();

		if (is_array($condition))
            $query->innerJoin($this->alias, $joinTable, $aliasJoin, implode(sprintf(' %s ', $type), $condition));
        else
            $query->innerJoin($this->alias, $joinTable, $aliasJoin, $condition);

        $this->setQueryBuilder($query);

        return $this;
	}

	public function leftJoin($joinTable, $aliasJoin, $condition, $type = 'AND')
	{
		$query = $this->Query();

		if (is_array($condition))
            $query->leftJoin($this->alias, $joinTable, $aliasJoin, implode(sprintf(' %s ', $type), $condition));
        else
            $query->leftJoin($this->alias, $joinTable, $aliasJoin, $condition);

        $this->setQueryBuilder($query);

        return $this;
	}

	public function rightJoin($joinTable, $aliasJoin, $condition, $type = 'AND')
	{
		$query = $this->Query();

		if (is_array($condition))
            $query->rightJoin($this->alias, $joinTable, $aliasJoin, implode(sprintf(' %s ', $type), $condition));
        else
            $query->rightJoin($this->alias, $joinTable, $aliasJoin, $condition);

        $this->setQueryBuilder($query);

        return $this;
	}

	public function where($key, $operator = '=', $value)
	{
		
		$where = sprintf('%s %s ?', $key, $operator);

		$query = $this->Query()
			 		  ->andWhere($where)
			 		  ->setParameter($this->countParams(), utf8_decode($value));

		$this->setQueryBuilder($query);
		
		return $this;
	}

	public function orWhere($key, $operator = '=', $value)
	{
		
		$where = sprintf('%s %s ?', $key, $operator);
		
		$query = $this->Query()
					  ->orWhere($where)
			  		  ->setParameter($this->countParams(), utf8_decode($value));

		$this->setQueryBuilder($query);
		
		
		
		return $this;
	}

	public function whereIn($key, array $values = array(), $type = 'AND')
	{
		if (!is_array($values))
            throw new \Exception("Invalid Parameter. Expect ARRAY parameter...", 500);

        
        if ($this->countParams() > 0)
        {
        	//$type = $this->Query()->getQueryPart('where')->getType();
     
           	$newValues = array();
        	foreach ($values as $k => $valueParam) 
        	{
        		$newValues[$k+($this->countParams())] = $valueParam;
        	}

        	$values = $newValues;
        }
 
        $where = sprintf('%s IN (%s)', $key,
        	implode(',',array_map(function($el)
        		{
        			return '?';
        		}, $values)));

        $query = $this->Query();
        
        if ($type === 'AND')
			$query->andWhere($where);
		else if (strtoupper($type) === 'OR')
			$query->orWhere($where);

		array_map(function($k, $el) use ($query)
		{
			$query->setParameter($k, utf8_decode($el));
		}, array_keys($values),$values);
		
		$this->setQueryBuilder($query);

		return $this;
	}

	private function wherePart()
	{
		$parts = $this::Query()->getQueryPart('where');
	
		return empty($parts) ? 0 : $parts->count();
	}

	private function countParams()
	{
		return count($this::Query()->getParameters());
	}

	public function orderBy($value, $sort = 'ASC')
	{
		$query = $this->Query()
			 		  ->orderBy($value, $sort);

		$this->setQueryBuilder($query);

		return $this;
	}

	public function addOrderBy($value, $sort = 'ASC')
	{
		$query = $this->Query()
			 		  ->addOrderBy($value, $sort);

		$this->setQueryBuilder($query);

		return $this;
	}

	public function firstResult($value)
	{
		$query = $this->Query()
			 		  ->setFirstResult($value);

		$this->setQueryBuilder($query);

		return $this;
	}

	public function maxResults($value)
	{
		$query = $this->Query()
			 		  ->setFirstResult($value);

		$this->setQueryBuilder($query);

		return $this;
	}

	public function setValue($key, $value)
	{
		$param = $this::paramsPart();

		$query = $this->Query()
			 		  ->set($key, '?')
			 		  ->setParameter($param, $value);

		$this->setQueryBuilder($query);

		return $this;
	}
}