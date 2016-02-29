<?php

namespace Vundi\Potato;

use Vundi\Potato\Exceptions\IDShouldBeNumber;

class Model
{
    private static $db;
    public $db_fields = [];
    public static $ID;
    protected static $child_class;

    public function __construct()
    {
        self::$db = new Database();
        self::$child_class = get_called_class();
    }

    /**
     * Will make it possible to assign key value pairs dynamically
     * in the child class
     */
    public function __set($key, $value)
    {
        $this->db_fields[$key] = $value;
    }

    /**
     * Save a record in the table
     * calls the insert method in the Database class
     */
    public function save()
    {
        $s = new static();

        return self::$db->insert($s::$entity_table, $this->db_fields);
    }

    /**
     * Update a record in the table
     */
    public function update()
    {
        $s = new static();

        $where = "id = {$s::$ID}";

        self::$db->update($s::$entity_table, $this->db_fields, $where);
    }

    /**
     * Remove a record from the table with the specified ID
     * @param  in $id ID of record you want to remove
     */
    public static function remove($id)
    {
        $s = new static();

        if (is_int($id)) {
            $where = "id = {$id}";
            self::$db->delete($s::$entity_table, $where);
        } else {
            throw new IDShouldBeNumber('Pass in an ID as the parameter, ID has to be a number', 1);
        }
    }

    /**
     * @param  int $id ID of the record to be retrieved
     * @return object  Instance of the Class
     */
    public static function find($id)
    {
        // var_dump(get_called_class());
        // die();
        $s = new static();

        if (is_int($id)) {
            //Set the ID of the class instance returned to $id since during update we shall update the
            //record with ID that matches the id passed during find
            $s::$ID = $id;

            $where = "id = {$id}";
            self::$db->select($s::$entity_table, $where);

            return self::$db->singleObject(self::$child_class);
        } else {
            throw new IDShouldBeNumber('Find only takes a number as a parameter', 1);
        }
    }

    public static function findWhere($conditions = array(), $fields = '*', $order = '', $limit = null, $offset = '')
    {
        $s = new static();
        $where = '';
        foreach ($conditions as $key => $value) {
            if (is_string($value)) {
                $where .= ' '.$key.' ="'.$value.'"'.' &&';
            } else {
                $where .= ' '.$key.' = '.$value.' &&';
            }
        }
        $where = rtrim($where, '&');
        self::$db->select($s::$entity_table, $where, $fields, $order, $limit, $offset);

        return self::$db->objectSet(self::$child_class);
    }

    // public static function findWherein($query)
    // {
    //     $s = new static();
    //     $results = self::$db->prepare($query);
    //     $results->setFetchMode(PDO::FETCH_ASSOC);

    //     return $results->fetch();
    // }

    /**
     * Finds all records in the table
     * @return array
     */
    public static function findAll()
    {
        $s = new static();

        self::$db->select($s::$entity_table);

        return self::$db->objectSet();
    }
}
