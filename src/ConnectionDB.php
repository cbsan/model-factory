<?php

/**
 *  @author    Cristian B. Santos <cristian.deveng@gmail.com>
 *  @copyright 2015 Bludata Software.
 *  @license   MIT
 * 
 * Estabelece conexão com a base de dados utilizando Doctrine Connection
 */

namespace CBSantos\ModelFactory;

use Doctrine\DBAL\Connection;

class ConnectionDB
{
	/**
	 * Armazena dados da conexão com a base de dados usando Doctrine
	 * @var Connection
	 */
	private static $connection;

	/**
	 * Cria conexão com a base de dados 
	 * @param type Connection $connection 
	 * @return Connection
	 */
	public function __construct(Connection $connection)
	{
		self::$connection = $connection;
		return $this;
	}

	/**
	 * Retorna a conexão efetuada com a base de dados 
	 * @return static Connection
	 */
	public static function db()
	{
		return self::$connection;
	}
}