  <?php

require 'vendor/autoload.php';

use Vundi\Potato\Model;

class Person extends Model
{
    public $FName;
    public $LName;
    public $Age;
    public $Gender;

    protected static $entity_table = 'Person';
    protected static $entity_class = 'Person';
    protected static $db_fields = array('ID', 'FName', 'LName', 'Age', 'Gender');
    public $primary_keys = array('ID');

    public function info()
    {
        return '#'.self::$ID.':'.$this->FName.' '.$this->LName.' '.$this->Age.' '.$this->Gender;
    }
}

echo print_r(Person::findAll());

echo 'Saved changes successfully';
