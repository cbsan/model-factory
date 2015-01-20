<?php

/**
 *  @author    Cristian B. Santos <cristian.deveng@gmail.com>
 *  @copyright 2015 Bludata Software.
 *  @license   MIT
 * 
 * Builder para as Query utilzadas, com metodos basicos (Get, GetById, Put, Delete)
 */

namespace CBSantos\ModelFactory\Builder;

use \CBSantos\ModelFactory\Builder\QueryContains;
use \CBSantos\ModelFactory\Providers\CollectionProvider;


abstract class QueryBuilder extends QueryContains implements QueryBuilderInterface
{

    /**
     * Cria a model de retorno
     * @param array $items 
     * @return model or collection
     */
	private function Model($items = array())
    {
         $collection = new CollectionProvider;
         
         return $collection->Collection($this, $items);
    }
	/**
     * Sets the value of Get.
     *
     * @param mixed $oi the Get
     *
     * @return self
     */
	public function Get()
	{
        $this->QueryBuilder();

		return $this::Model($this::Query()->execute()->fetchAll());
	}

	/**
     * Sets the value of GetById.
     *
     * @param mixed $id the GetById
     *
     * @return self
     */
	public function GetById($id)
	{
        $this->QueryBuilder();

        $this->where($this->primaryKey,'=',$id);

		return $this::Model($this::Query()->execute()->fetch());	
	}

    /**
     * Sets the value of Save.
     *
     * @param mixed $input the Save
     *
     * @return object
     */
    public function Save(array $input)
    {
        $id = NULL;
        $this::beginTransaction();

        try
        {
            if (!is_array($input))
                throw new \Exception('Request invalid', 400);

            $query = $this->Query();
            $query->insert($this->table);

            $c = 0;
            foreach ($input as $key => $value) 
            {
                if ($this->primaryKey === $key)
                    $this->id = $value;

                $query->setValue($key, '?')
                      ->setParameter($c, $value);
                $c++;
            }
            $query->execute();

            $id = $this::lastId();

            $this::commit();
        }catch (\Exception $e)
        {
            $this::rollback();
            throw new \Exception($e->getMessage(), 500);
        }

        return $this::GetById($id);
    }

	/**
     * Sets the value of Update.
     *
     * @param mixed $input the Put
     *
     * @return self
     */
	public function Put(array $input)
	{
        
        $this::beginTransaction();

        try
        {
            if (!is_array($input))
                throw new \Exception('Request invalid', 400);

            $query = $this->Query();
            $query->update($this->table);

            $c = 0;
            foreach ($input as $key => $value) 
            {
                if ($key !== $this->primaryKey)
                {
                    $query->set($key, '?')
                          ->setParameter($c, $value);
                    $c++;
                } else
                    $where = sprintf('%s = ?', $key);
            }

            $query->where($where)
                  ->setParameter($c, $input[$this->primaryKey]);


            $query->execute();

            $this::commit();
        }catch (\Exception $e)
        {
            $this::rollback();
            throw new \Exception($e->getMessage(), 500);
        }
        
        return $this::GetById($input[$this->primaryKey]);
	}

	/**
     * Sets the value of Delete.
     *
     * @param mixed $input the Delete
     *
     * @return self
     */
	public function Delete($id)
	{
       
		$this::beginTransaction();

        try
        {
            if (empty($id))
                throw new \Exception('Required "id" reference', 400);

            $query = $this->Query();
            $query->delete($this->table)
                  ->where(sprintf('%s = ?', $this->primaryKey))
                  ->setParameter(0, $id);

            $query->execute();

            $this::commit();
        }catch (\Exception $e)
        {
            $this::rollback();
            throw new \Exception($e->getMessage(), 500);
        }

        return $this;
	}

    /**
     * Define os dados padrão da model requisitada
     * @return model
     */
    public function defaultModel()
    {
        /*
        |=================================================================
        | Set's attributes in model
        |=================================================================
        |
        */
        $defaultModel = $this::Query()->execute()->fetch();

        $this->fieldsLabels  = array_keys($defaultModel);
        
        return $this;
    }
}