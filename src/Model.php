<?php

namespace Vundi\Potato;

class Model
{
    private static $db;
    protected static $entity_table;
    protected static $entity_class;
    protected static $db_fields;
    public static $ID;

    public function __construct()
    {
        self::$db = new Database();
    }

    public function save()
    {
        $s = new static();
        foreach ($s::$db_fields as $key) {
            $data[$key] = $this->$key;
        }

        return self::$db->insert($s::$entity_table, $data);
    }

    public function update()
    {
        $s = new static();
        foreach ($s::$db_fields as $key) {
            if (!is_null($key)) {
                $data[$key] = $this->$key;
            }
        }
        $where = "id = {$s::$ID}";

        self::$db->update($s::$entity_table, $data, $where);
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
