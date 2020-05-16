<?php
namespace Adapter;

class Database
{
    private static $instances = [];

    public $pdo;

    private function __construct($host, $user, $pass, $dbName)
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$dbName", $user, $pass,
        array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    public static function setInstance($host, $user, $pass, $dbName, $instanceName)
    {
        self::$instances[$instanceName] = new Database($host, $user, $pass, $dbName);
    }

    public static function getInstance($instanceName)
    {
       return self::$instances[$instanceName];
    }

    public function getpdo()
    {
        return $this->pdo;
    }

    /**
     * close the database connection
     */
    public function __destruct() {
        $this->pdo = null;
    }
}

