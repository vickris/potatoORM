<?php

namespace Vundi\Potato;

use PDO;
use PDOException;

class Connection
{
    //Instance variables for db connection
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $dbtype;

    public static $db_handler;
    public static $statement;

    public function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASS');
        $this->dbname = getenv('DB_NAME');
        $this->dbtype = getenv('DB_TYPE');

        $dsn = $this->dbtype.':host='.$this->host.';dbname='.$this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            );

        try {
            self::$db_handler = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            echo $e->getmessage();
        }
    }
}
