  <?php

require 'vendor/autoload.php';

use Vundi\Potato\Model;

class Person extends Model
{
    protected static $entity_table = 'Person';
    protected static $entity_class = 'Person';

    public function info()
    {
        return '#'.self::$ID.':'.$this->FName.' '.$this->LName.' '.$this->Age.' '.$this->Gender;
    }
}

$person = Person::find(20);
$person->FName = 'Ganga';
$person->LName = 'Chris';
$person->update();

echo 'Saved changes successfully';
