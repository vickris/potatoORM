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

    public function __set($key, $value)
    {
        $this->db_fields[$key] = $value;
    }

    public function save()
    {
        //var_dump($this->db_fields);
        $s = new static();
        // foreach ($s::$db_fields as $key) {
        //     $data[$key] = $this->$key;
        // }

        return self::$db->insert($s::$entity_table, $this->db_fields);
    }

    public function update()
    {
        $s = new static();

        $where = "id = {$s::$ID}";

        self::$db->update($s::$entity_table, $this->db_fields, $where);
    }

    public static function remove($id)
    {
        if (is_int($id)) {
            $where = "id = {$id}";
            $s = new static();
            self::$db->delete($s::$entity_table, $where);
        } else {
            throw new IDShouldBeNumber('Pass in an ID as the parameter, ID has to be a number', 1);
        }
    }

    public static function find($id)
    {
        if (is_int($id)) {
            $s = new static();
            //comment
            $s::$ID = $id;

            $where = "id = {$id}";
            self::$db->select($s::$entity_table, $where);

            return self::$db->singleObject($s::$entity_class);
        } else {
            throw new IDShouldBeNumber('Find only takes a number as a parameter', 1);
        }
    }

    public static function findAll()
    {
        $s = new static();

        self::$db->select($s::$entity_table);

        return self::$db->objectSet();
    }
}
