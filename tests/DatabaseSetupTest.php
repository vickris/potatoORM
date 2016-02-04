<?php

namespace Vundi\Potato\Test;

use PHPUnit_Framework_TestCase;
use PDO;

class DatabaseSetupTest extends PHPUnit_Framework_TestCase
{
    protected $car;
    protected $DB;

    public function setUp()
    {
        $connection = [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'dbname' => 'potato',
            'dbtype' => 'mysql',
        ];

        $dsn = $connection['dbtype'].':host='.$connection['host'].';dbname='.$connection['dbname'];

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );

        $this->DB = new PDO($dsn, $connection['user'], $connection['pass'], $options);
        $sql = 'CREATE TABLE car'
            .' (ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,'
            .' make TEXT,'
            .' year INT);';

        $statement = $this->DB->prepare($sql);
        $statement->execute();

        $this->car = new Car();
    }

    public function seedData()
    {
        $sql = 'INSERT INTO car (make, year) '.
                "VALUES ('Mercedes', 1994)";

        $statement = $this->DB->prepare($sql);
        $statement->execute();
    }

    public function tearDown()
    {
        $sql = 'DROP TABLE car';
        $statement = $this->DB->prepare($sql);
        $statement->execute();
    }

    public function testSave()
    {
        $this->car->make = 'BMW';
        $this->car->year = 2004;
        $condition = $this->car->save();
        $this->assertTrue($condition);
    }
}
