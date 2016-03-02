<?php

require 'vendor/autoload.php';

use Vundi\Potato\Model;
use Vundi\Potato\Exceptions\NonExistentID;
use Vundi\Potato\Exceptions\IDShouldBeNumber;

class Person extends Model
{
    protected static $entity_table = 'Person';
}

try {

    $set = (Person::findWhere(['FName' => 'kimaer', 'LName' => 'Chris']));
    if (array_key_exists('id', $set[0])) {
        echo "Key exists in database";
    } else {
        echo "Key does not exist";
    }

    //var_dump(Person::findAll());

    // $person = Person::find(29);
    // $person->FName = "Koech";
    // $person->update();


    /*$person = new Person();
    $person->FName = "Mahad";
    $person->gshdh = "Kimeu";
    $person->Age = 33;
    $person->save();*/


    //Person::remove('gshd');
} catch (NonExistentID $e) {
    echo $e->getMessage();
} catch (IDShouldBeNumber $e) {
    echo $e->getMessage();
}

//echo 'Saved changes successfully';
