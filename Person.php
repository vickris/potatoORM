  <?php

require 'vendor/autoload.php';

use Vundi\Potato\Model;

class Person extends Model
{
    protected static $entity_table = 'Person';
    protected static $entity_class = 'Person';
}

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

try {
    var_dump(Person::findAll());
} catch (Exception $e) {
    echo $e->getMessage();
}

//echo 'Saved changes successfully';

