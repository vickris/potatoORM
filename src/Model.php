<?php

namespace Vundi\Potato;

use Vundi\Potato\Exceptions\IDShouldBeNumber;

class Model
{
    private static $db;
    protected static $entity_table;
    protected static $entity_class;
    protected $db_fields = [];
    public static $ID;

    public function __construct()
    {
        self::$db = new Database();
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
        $s = new static();

        if (is_int($id)) {
            //Set the ID of the class instance returned to $id since during update we shall update the
            //record with ID that matches the id passed during find
            $s::$ID = $id;

            $where = "id = {$id}";
            self::$db->select($s::$entity_table, $where);

            return self::$db->singleObject($s::$entity_class);
        } else {
            throw new IDShouldBeNumber('Find only takes a number as a parameter', 1);
        }
    }

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
