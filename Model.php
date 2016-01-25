<?php

class Model
{
    private static $db;
    protected static $entity_table = 'Person';

    public function __construct()
    {
        self::$db = new Database;
    }

    public function add()
    {
        foreach ($this->db_fields as $key) {
            $data[$key] = $this->$key;
        }
        $this->db->insert($this->entity_table, $data);
    }

    public function update()
    {
        foreach ($this->db_fields as $key) {
            if (!is_null($this->key)) {
                $data[$key] = $this->$key;;
            }
        }
        $where = ' ';
        foreach ($primary_keys as $key) {
            $where .=' '.$key ." = ".$this->$key." &&";
        }

        $where = rtrim($where, '&');
        $this->db->update($this->entity_table, $data, $where);
    }

    public static function remove($id)
    {
        $where = "id = '$id'";
        new static();
        self::$db->delete(self::$entity_table, $where);
    }

    public static function find($id)
    {
        $where = "id = '$id'";
        new static();
        self::$db->single(self::$entity_table, $where);
    }
}