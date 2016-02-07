<?php

namespace Vundi\Potato\Test;

use PHPUnit_Framework_TestCase;
use Dotenv\Dotenv;
use Vundi\Potato\Database;

class DatabaseSetupTest extends PHPUnit_Framework_TestCase
{
    protected $car;
    protected $DB;
    public function setUp()
    {
        try {
            $dotenv = new Dotenv(substr(__DIR__, 0, -5));
            $dotenv->load();
        } catch (Exception $e) {
            // in heroku we don't have .env
        }
        $this->DB = new Database();
        $sql = 'CREATE TABLE Car'
            .' (ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,'
            .' make TEXT,'
            .' year INT);';

        $statement = Database::$db_handler->prepare($sql);
        $statement->execute();

        $this->car = new Car();
    }

    public function seedData()
    {
        $sql1 = 'INSERT INTO Car (make, year) '.
                "VALUES ('Mercedes', 1994)";
        $sql2 = 'INSERT INTO Car (make, year) '.
                "VALUES ('audi', 2005)";
        $sql3 = 'INSERT INTO Car (make, year) '.
                "VALUES ('Lexus', 2014)";

        $statement1 = Database::$db_handler->prepare($sql1);
        $statement1->execute();
        $statement2 = Database::$db_handler->prepare($sql2);
        $statement2->execute();
        $statement3 = Database::$db_handler->prepare($sql3);
        $statement3->execute();
    }

    public function tearDown()
    {
        $sql = 'DROP TABLE Car';
        $statement = Database::$db_handler->prepare($sql);
        $statement->execute();
    }

    public function testTableName()
    {
        echo Car::$entity_table;
        $this->assertEquals('Car', Car::$entity_table);
    }

    public function testSave()
    {
        $this->car->make = 'BMW';
        $this->car->year = 2004;
        $condition = $this->car->save();
        $this->assertTrue($condition);
    }

    public function testAll()
    {
        $collection = Car::findAll();
        $this->assertEquals(3, count($collection));
    }
}
