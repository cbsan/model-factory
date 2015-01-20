<?php

/**
 *  @author    Cristian B. Santos <cristian.deveng@gmail.com>
 *  @copyright 2015 Bludata Software.
 *  @license   MIT
 * 
 * Cria o Core para ser utilizado nas QUERY
 */

namespace CBSantos\ModelFactory\Builder;

use \CBSantos\ModelFactory\ConnectionDB;
use \Doctrine\DBAL\Query\QueryBuilder;

abstract class QueryCore
{
	private static $Query;

	/**
     * Gets the value of queryBuilder.
     *
     * @return mixed
     */
    public function QueryBuilder()
    {
    	/*
		|Sets parameters from query
		 */
		$query = ConnectionDB::db()->createQueryBuilder();
		
		$query->select($this->fields)
			  ->from($this->table, $this->alias)
			  ->setFirstResult($this->firstResult)
			  ->setMaxResults($this->maxResults);

        self::$Query = $query;

        return self::$Query;
    }

    /**
     * Retorna as ForeignKey da model
     * @return foreignkey
     */
    public function getColumnsFK()
    {
        
        $_fk = array();
        foreach ($this->getSchemaDB()->listTableForeignKeys($this->table) as $key => $value) 
        {
            
            $_fk[] = array(
                        'table'  => $value->getName(),
                        'fields' => $value->getLocalColumns()
                    );
        }

        return $_fk;
    }

    /**
     * Schema da Conexao 
     * @return SchemaManager
     */
    public function getSchemaDB()
    {
        return ConnectionDB::db()->getSchemaManager();
    }

    /**
     * Set's driver queryBuilder 
     * @param QueryBuilder $query [description]
     */
    public function setQueryBuilder(QueryBuilder $query)
    {
    	
    	self::$Query = $query;

    	return $this;
    }

    public function Query()
    {
    	return !isset(self::$Query) && empty(self::$Query) ? $this->QueryBuilder() : self::$Query;
    }

    /**
     * Coloca base de dados em modo de transaction, desativando commit automatico
     * @return [type] [description]
     */
    public function beginTransaction()
    {
        ConnectionDB::db()->beginTransaction();
    }

    /**
     * Efetua a requisição SQL caso teja sido elaborada com sucesso
     * @return type
     */
    public function commit()
    {
         ConnectionDB::db()->commit();   
    }

    /**
     * Retorna a transaction da base de dados
     * @return type
     */
    public function rollback()
    {
        ConnectionDB::db()->rollback();
    }

    /**
     * Retorna ultimo Id inserido na conexão
     * @return type
     */
    public function lastId()
    {
        return ConnectionDB::db()->lastInsertId();
    }
}