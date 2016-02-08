<?php

namespace Vundi\Potato;

use PDO;
use PDOException;
use Vundi\Potato\Exceptions\NonExistentID;

class Database
{
    //Instance variables for db connection
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $dbtype;

    public static $db_handler;
    private static $statement;

    public function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASS');
        $this->dbname = getenv('DB_NAME');
        $this->dbtype = getenv('DB_TYPE');

        try {
            if ($this->dbtype == 'sqlite') {
                $dsn = $this->dbtype.':'.$this->dbname;
                self::$db_handler = new PDO($dsn);
            } else {
                $dsn = $this->dbtype.':host='.$this->host.';dbname='.$this->dbname;
                self::$db_handler = new PDO($dsn, $this->user, $this->pass);
            }

            self::$db_handler->setAttribute(PDO::ATTR_PERSISTENT, true);
            self::$db_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getmessage();
        }
    }

    public function prepare($query)
    {
        self::$statement = self::$db_handler->prepare($query);
    }

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
        self::$statement->bindValue($param, $value, $type);
    }

    /**
     * `
     * Execute statements passed in as queries.
     */
    public function execute()
    {
        self::$statement->execute();
    }

    public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = '')
    {
        $query = "SELECT $fields FROM $table "
                 .($where ? " WHERE $where " : '')
                 .($limit ? " LIMIT $limit " : '')
                 .(($offset && $limit ? " OFFSET $offset " : ''))
                 .($order ? " ORDER BY $order " : '');
        $this->prepare($query);
    }

    public function insert($table, $data)
    {
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $query = "INSERT INTO $table ($fieldNames) VALUES($fieldValues)";
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
     * Update data in an existing row.
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
     * Return Objectset.
     */
    public function objectSet()
    {
        $this->execute();
        self::$statement->setFetchMode(PDO::FETCH_ASSOC);

        return self::$statement->fetchAll();
    }

    /**
     * Return single object.
     */
    public function singleObject($entityClass)
    {
        $this->execute();
        self::$statement->setFetchMode(PDO::FETCH_CLASS, $entityClass);
        $results = self::$statement->fetch();

        if (empty($results)) {
            throw new NonExistentID('Could not find that record, pass a record ID that exists', 1);
        } else {
            return $results;
        }
    }

    /**
     * Return number of rows in the table.
     *
     * @return int
     */
    public function rowCount()
    {
        return self::$statement->rowCount();
    }
}
