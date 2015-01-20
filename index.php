<?php

require_once('./vendor/autoload.php');

use \CBSantos\ModelFactory\ConnectionDB;
use \CBSantos\ModelFactory\Providers\ModelProvider;

/*
|=================================================================
| Defined Model Business - Test
|=================================================================
|
*/
class Business extends ModelProvider
{
	protected $table      = 'MODEL.Business';
	protected $primaryKey = 'Id';
}


/*
|=================================================================
| Defined Model Users - Test
|=================================================================
|
*/
class Users extends ModelProvider
{
	protected $table      = 'MODEL.Users';
	protected $primaryKey = 'Id';

	public function Business()
	{
		return $this->hasOne(new Business,'Id','Id');
	}
}

/*
|=================================================================
| Connected in DataBase using Doctrine Connection
|=================================================================
|
*/
$config = new \Doctrine\DBAL\Configuration;

$params['dbname']   = 'nameDataBase';
$params['user']     = 'userDB';
$params['password'] = 'passwordDB';
$params['port']     = 'portConnection';
$params['host']     = 'hostConnection';
$params['driver']   = 'driverConnection';
$params['charset']  = 'charset';
$params['server']   = 'serverConnection';

/*
|=================================================================
| Informer connection DB
|=================================================================
|
*/
new ConnectionDB(\Doctrine\DBAL\DriverManager::getConnection($params, $config));


//Init Model's Interface
$users = new Users;
$business = new Business;

//Operations:
$users->Get();       //Select all model
$users->GetById(??); //Select element by Id
$users->Put(??);     //Update in request input
$users->Delete(??);  //Delete element by Id - Reference in ForeignKey Model