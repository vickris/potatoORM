<?php

class Model
{
    private static $db;
    protected static $entity_table = 'Person';
    protected static $entity_class = 'Person';
    protected static $db_fields = array('FName', 'LName', 'Age', 'Gender');
    public static $ID;

    public function __construct()
    {
        self::$db = new Database();
    }

    public function save()
    {
        foreach (self::$db_fields as $key) {
            $data[$key] = $this->$key;
        }
        self::$db->insert(self::$entity_table, $data);
    }

    public function update()
    {
        foreach (self::$db_fields as $key) {
            if (!is_null($key)) {
                $data[$key] = $this->$key;
            }
        }
        $where = "id = '".self::$ID."'";

        $where = rtrim($where, '&');
        self::$db->update(self::$entity_table, $data, $where);
    }

    public static function remove($id)
    {
        $where = "id = '$id'";
        new static();
        self::$db->delete(self::$entity_table, $where);
    }

    public static function find($id)
    {
        new static();
        self::$ID = $id;
        $where = "id = '$id'";
        self::$db->select(self::$entity_table, $where);

        return self::$db->singleObject(self::$entity_class);
    }

    public static function findAll()
    {
        new static();
        self::$db->select(self::$entity_table);

        return self::$db->objectSet(self::$entity_class);
    }
}
