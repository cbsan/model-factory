<?php

/**
 *  @author    Cristian B. Santos <cristian.deveng@gmail.com>
 *  @copyright 2015 Bludata Software.
 *  @license   MIT
 * 
 */

namespace CBSantos\ModelFactory\Builder;



interface QueryBuilderInterface
{
	public function Get();
	public function GetById($id);
	public function Save(array $input);
	public function Put(array $input);
	public function Delete($id);

}