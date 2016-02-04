<?php

namespace Vundi\Potato\Test;

use Vundi\Potato\Model;

/**
 * Car class will be the testing class.
 */
class Car extends Model
{
    public $make;
    public $year;

    protected static $entity_table = 'car';
    protected static $entity_class = 'Car';
    protected static $db_fields = array('make', 'year');
}
