<?php

require "Database.php";
//require "DBContext.php";
//require "EntityState.php";
require "Entity.php";

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

/*$db = new DBContext();

$entity1 = new Person();

$entity1->FName = "Joan";
$entity1->LName = "Ngatia";
$entity1->Gender = "Female";
$entity1->Age = 21;
$db->add($entity1);

$entity2 = new Person();

$entity2->ID = 1;
$entity2->FName = "Stan";
$entity2->LName = "MD";
$db->update($entity2);

$entity3 = new Person();
$entity3->ID = 3;
$db->remove($entity3);

$db->saveChanges();
echo "Saved changes successfully";
*/

/*$person = $db->find(new Person(), array('ID' => 6));
echo $person->FName;
var_dump($db->findAll(new Person()));
*/

// $newEntity = new Person();
// $newEntity->FName = "Mariam";
// $newEntity->LName = "koste";
// $newEntity->Gender = "Female";
// $newEntity->Age = 24;
// $newEntity->add();

Person::remove(16);

echo "Saved changes successfully";
