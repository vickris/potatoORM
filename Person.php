<?php

require "Database.php";
require "Model.php";

class Person extends Model
{
    public $ID;
    public $FName;
    public $LName;
    public $Age;
    public $Gender;

    protected static $entity_table = 'Person';
    public $entity_class = 'Person';
    public $db_fields = array('ID', 'FName', 'LName', 'Age', 'Gender');
    public $primary_keys = array('ID');

    public function info()
    {
        return '#'.$this->ID.':'.$this->FName.' '.$this->LName.' '.$this->Age.' '.$this->Gender;
    }
}

Person::remove(15);

echo "Saved changes successfully";
