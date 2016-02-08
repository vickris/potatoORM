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
        new Database();
        $sql = 'CREATE TABLE Car'
            .' (ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,'
            .' make TEXT,'
            .' year INT);';

        $statement = Database::$db_handler->prepare($sql);
        $statement->execute();

        $this->seedData();

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
        var_dump(Database::$db_handler);
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

    public function testFindAll()
    {
        $collection = Car::findAll();
        $this->assertCount(3, $collection);
    }

    public function testFindOne()
    {
        $car = Car::find(1);
        $this->assertArrayHasKey('make', $car);
        $this->assertEquals('Mercedes', $car['make']);
    }

    // public function testUpdateFunction()
    // {
    //     $this->car = Car::find(1);
    //     $this->car['make'] = 'Porshe';
    //     $this->car->update();
    //     var_dump($car);
    // }

    public function testDeleteWorks()
    {
        $res = Car::remove(1);
        $count = Car::findAll();
        $this->assertCount(2, $count);
    }

    /**
     * @expectedException Vundi\Potato\Exceptions\NonExistentID
     */
    public function testThrowsExceptionWhenDeletingNonExistentId()
    {
        $res = Car::remove(23444);
    }

    /**
     * @expectedException Vundi\Potato\Exceptions\IDShouldBeNumber
     */
    public function testThrowsExceptionWhenIDPassedIsNotANumber()
    {
        $res = Car::remove('water');
    }

    /**
     * @expectedException Vundi\Potato\Exceptions\NonExistentID
     */
    public function testThrowsExceptionWhenFindingANonExiestentRecord()
    {
        $res = Car::find(23444);
    }
}
