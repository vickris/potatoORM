<?php

namespace Vundi\Potato;

use PDO;
use PDOException;
use Vundi\Potato\Exceptions\NonExistentID;
use Dotenv;
use Dotenv\Exception\InvalidPathException;

class Database
{
    //Instance variables for db connection
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $dbtype;
    public $dotenv;

    public static $db_handler;
    private static $statement;

    public function __construct()
    {
        try {
            $this->dotenv = new Dotenv\Dotenv(substr(__DIR__, 0, -3));
            $this->dotenv->load();
        } catch (InvalidPathException $e) {

        }


        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASS');
        $this->dbname = getenv('DB_NAME');
        $this->dbtype = getenv('DB_TYPE');

        try {
            //get a new pdo connection depending on the database driver specified
            if ($this->dbtype == 'sqlite') {
                $dsn = $this->dbtype.':'.$this->dbname;
                self::$db_handler = new PDO($dsn);
            } else {
                $dsn = $this->dbtype.':host='.$this->host.';dbname='.$this->dbname;
                self::$db_handler = new PDO($dsn, $this->user, $this->pass);
            }

            /**
             * set attributes
             * PDO::ATTR_PERSISTENT sets the connection type to the database to be persistent
             * PDO::ATTR_ERRMODE will make PDO use exceptions to handle errors
             */
            self::$db_handler->setAttribute(PDO::ATTR_PERSISTENT, true);
            self::$db_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //echo any errors that might occur during connection
            echo $e->getmessage();
        }
    }

    /**
     * prepare statements prevent sql injection
     * by allowing you to bind values into your SQL statements
     */
    public function prepare($query)
    {
        self::$statement = self::$db_handler->prepare($query);
    }

    /**
     * bind the inputs with the placeholders we put in place
     * @param  string $param placeholdder name eg :name
     * @param  $value actual value that we want to bind to the placeholder e.g. Prosper
     * @param  sttring $type is the datatype of the parameter, example string.
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        //then run PDO bindValue on the statement to bind the values
        self::$statement->bindValue($param, $value, $type);
    }

    /**
     * Executes the prepared statement
     */
    public function execute()
    {
        self::$statement->execute();
    }

    /**
     * The select method allows to to specify different inputs to enable
     *  you to run various select queries.
     */
    public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = '')
    {
        $query = "SELECT $fields FROM $table "
                 .($where ? " WHERE $where " : '')
                 .($order ? " ORDER BY $order " : '')
                 .($limit ? " LIMIT $limit " : '')
                 .(($offset && $limit ? " OFFSET $offset " : ''));
                 
        $this->prepare($query);
    }

    /**
     * Insert data into the table
     * @param  string $table table in which you want to insert values
     * @param  array $data  an array containing names of fields and the
     * data ypu want to enter
     * @return BOOL true or false depending on the success
     */
    public function insert($table, $data)
    {
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $query = "INSERT INTO $table ($fieldNames) VALUES($fieldValues)";
        $this->prepare($query);

        /**
         * Iterate through data then bind the values
         */
        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        try {
            $this->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->errorInfo[2];

            return false;
        }
    }

    /**
     * Update data in the table
     * @param  string $table Table to be updated
     * @param  array $data  Key value pairs which you want to update
     * @param  string $where condition, in our case ID
     */
    public function update($table, $data, $where = '')
    {
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $query = "UPDATE $table SET $fieldDetails ".($where ? 'WHERE '.$where : '');
        $this->prepare($query);

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }
        try {
            $this->execute();

            return true;
        } catch (PDOException $e) {
            echo $e->errorInfo[2];

            return false;
        }
    }

    /**
     * Delete row from database.
     */
    public function delete($table, $where)
    {
        $this->prepare("DELETE FROM $table WHERE $where");

        $this->execute();
        $num = $this->rowCount();

        if ($num < 1) {
            throw new NonExistentID('Cannot delete the record with that ID since it is non existent');
        }
    }

    /**
     * Return an array containing all the records
     * @param string $clazz Class name of the model implementation to load
     */
    public function objectSet($clazz)
    {
        $this->execute();
        self::$statement->setFetchMode(PDO::FETCH_CLASS, $clazz);

        return self::$statement->fetchAll();
    }

    /**
     * Return single object.
     */
    public function singleObject($entity_class)
    {
        $this->execute();
        self::$statement->setFetchMode(PDO::FETCH_CLASS, $entity_class);
        $results = self::$statement->fetch();

        if (empty($results)) {
            throw new NonExistentID('Could not find that record, pass a record ID that exists', 1);
        } else {
            return $results;
        }
    }

    /**
     * returns the number of effected rows from the previous delete, update or insert statement
     * @return int
     */
    public function rowCount()
    {
        return self::$statement->rowCount();
    }
}
