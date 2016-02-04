<?php

namespace Vundi\Potato;

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
        //$s = new static();
        // foreach ($s::$db_fields as $key) {
        //     $data[$key] = $this->$key;
        // }

        return self::$db->insert('Person', $this->db_fields);
    }

    public function update()
    {
        var_dump($this);
        $s = new static();

        $where = "id = {$s::$ID}";

        self::$db->update($s::$entity_table, $this->db_fields, $where);
    }

    public static function remove($id)
    {
        $where = "id = {$id}";
        $s = new static();
        self::$db->delete($s::$entity_table, $where);
    }

    public static function find($id)
    {
        $s = new static();

        //comment
        $s::$ID = $id;

        $where = "id = {$id}";
        self::$db->select($s::$entity_table, $where);

        return self::$db->singleObject($s::$entity_class);
    }

    public static function findAll()
    {
        $s = new static();

        self::$db->select($s::$entity_table);

        return self::$db->objectSet($s::$entity_class);
    }
}
