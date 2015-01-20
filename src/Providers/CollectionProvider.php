<?php

namespace CBSantos\ModelFactory\Providers;

use \Doctrine\Common\Collections\ArrayCollection;


class CollectionProvider
{

	private $items = array();
	

	public function Collection($model, $items = array())
	{	
		if (empty($items))
			return $model;
		
		foreach ($items as $key => $value) 
		{
			if (is_array($value))
			{
				$this->items[] = $this::model($model, $value);
			} else
			{
				return $this::model($model, $items);
			}
		}
		return new ArrayCollection($this->items);
	}

	private static function model($model, $array = array())
	{
		$cloneModel = new $model;

		$cloneModel->fieldsLabels  = array_keys($array);
		$cloneModel->attributes    = $array;
		$cloneModel->rawAttributes = $array;
		$cloneModel->relations     = $model->getColumnsFK();		
		$cloneModel->id            = $array[$cloneModel->primaryKey];
		
		return $cloneModel;
	}
}