<?php

/**
 *  @author    Cristian B. Santos <cristian.deveng@gmail.com>
 *  @copyright 2015 Bludata Software.
 *  @license   MIT
 * 
 * Builder para as Query utilzadas, com metodos basicos (Get, GetById, Put, Delete)
 */

namespace CBSantos\ModelFactory\Providers;

use \CBSantos\ModelFactory\Builder\QueryBuilder;
use \CBSantos\ModelFactory\Providers\QuerySerialize;

abstract class ModelProvider extends QueryBuilder implements  QuerySerializeInterface
{
	protected $table;
	protected $alias = 't';
	protected $fields = '*';
	protected $fieldsLabels;
	protected $primaryKey;
	protected $attributes;
	protected $rawAttributes;
	protected $firstResult = 0;
	protected $maxResults = 100;

    public function __construct()
    {
        $this->defaultModel();
    }

	public function __set($atrib, $value)
    {
        $this->$atrib = $value;
    }
 
    public function __get($atrib)
    {

        if (empty($this->$atrib) && !empty($this->fieldsLabels))
            if (in_array($atrib, $this->fieldsLabels))
                return $this->attributes[$atrib];

        return $this->$atrib;
    }

    /**
     * Serialized model in Json 
     * @return json
     */
    public function toJson()
    {
        return json_encode($this->rawAttributes);
    }

   /**
     * Serialized model in Array 
     * @return array
     */
    public function toArray()
    {
        return $this->rawAttributes;
    }

    /**
     * Serialized model in Array 
     * @return array
     */
    public function toObject()
    {
        return (object)$this->rawAttributes;
    }

    public function hasMany($model, $key, $foreignKey)
    {
        $model = new $model;
        $model->primaryKey = $foreignKey;

        $model->where($key,'=', $this->$key);
        
        return $model->Get();
    }

    public function hasOne($model, $key, $foreignKey)
    {
        $model = new $model;
        $model->primaryKey = $foreignKey;
        
        return $model->GetById($this->$key);
    }
}
