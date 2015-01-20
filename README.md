# modelFactory
Factory mapping model DB - Uses DoctrineDBAL

## Create Connection DataBase:

**Connected in DataBase using Doctrine Connection**

```
use \CBSantos\ModelFactory\ConnectionDB;

$config = new \Doctrine\DBAL\Configuration;

$params['dbname']   = 'nameDataBase'; 
$params['user']     = 'userDB';
$params['password'] = 'passwordDB'; 
$params['port']     = 'portConnection'; 
$params['host']     = 'hostConnection'; 
$params['driver']   = 'driverConnection'; 
$params['charset']  = 'charset'; 
$params['server']   = 'serverConnection'; 

- Information connection DB - Static method connection

new ConnectionDB(\Doctrine\DBAL\DriverManager::getConnection($params, $config));
```
##Example Using:

**Defined Model Users - Test**

```
use \CBSantos\ModelFactory\Providers\ModelProvider;

class Users extends ModelProvider
{

    protected $table      = 'MODEL.Users';
    protected $primaryKey = 'Id';

    public function Business()
    {
        return $this->hasOne(new Business,'Id','Id');
    }
}

```

**Init Model's Interface**

```
$users = new Users;

*Basic Operations*:

* $users->Get();       //Select all model
* $users->GetById(??); //Select element by Id
* $users->Put(??);     //Update in request input
* $users->Delete(??);  //Delete element by Id - Reference in ForeignKey Model
```

================





