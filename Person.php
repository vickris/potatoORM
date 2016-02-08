  <?php

require 'vendor/autoload.php';

use Vundi\Potato\Model;
use Vundi\Potato\Exceptions\NonExistentID;
use Vundi\Potato\Exceptions\IDShouldBeNumber;

class Person extends Model
{
    protected static $entity_table = 'Person';
    protected static $entity_class = 'Person';
}

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

try {
    var_dump(Person::find(2));
} catch (NonExistentID $e) {
    echo $e->getMessage();
} catch (IDShouldBeNumber $e) {
    echo $e->getMessage();
}

//echo 'Saved changes successfully';

