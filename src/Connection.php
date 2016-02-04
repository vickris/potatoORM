<?php

namespace Vundi\Potato;

class Connection
{
    //Instance variables for db connection
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'potato';
    private $dbtype = 'mysql';

    private static $db_handler;
    private static $statement;

    public function __construct()
    {
        $dsn = $this->dbtype.':host='.$this->host.';dbname='.$this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );

        try {
            self::$db_handler = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
