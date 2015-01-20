<?php

/**
 *  @author    Cristian B. Santos <cristian.deveng@gmail.com>
 *  @copyright 2015 Bludata Software.
 *  @license   MIT
 * 
 * Opções de serialização de dados retornados nas models
 */

namespace CBSantos\ModelFactory\Providers;

interface QuerySerializeInterface
{
	public function toJson();
	public function toArray();
}