<?php

namespace Vundi\Potato\Test;

use PHPUnit_Framework_TestCase;
use Dotenv\Dotenv;
use Vundi\Potato\Database;

class DatabaseSetupTest extends PHPUnit_Framework_TestCase
{
    protected $car;

    /**
     * Will be called before any test has run
     */
    public static function setUpBeforeClass()
    {
        try {
            //load teh dot env variables for connection
            $dotenv = new Dotenv(__DIR__);
            $dotenv->load();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        new Database();

        $sql = 'CREATE TABLE IF NOT EXISTS `Car`  (
            `ID`    INTEGER PRIMARY KEY AUTOINCREMENT,
            `make`  TEXT,
            `Year`  INTEGER
        );';

        $statement = Database::$db_handler->prepare($sql);
        $statement->execute();

        self::seedData();
    }

    /**
     * Will be called before every test is run.
     */
    public function setup()
    {
        $this->car = new Car();
    }

    /**
     * Prepopulate the database with data
     */
    public static function seedData()
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

    /**
     * Will be called after all the tests have run
     */
    public static function tearDownAfterClass()
    {
        $sql = 'DROP TABLE Car';
        $statement = Database::$db_handler->prepare($sql);
        $statement->execute();
    }

    /**
     * Tests if the table name Passed in the class is to that created in the
     * database
     */
    public function testTableName()
    {
        $this->assertEquals('Car', Car::$entity_table);
    }

    /**
     * Test to see if save is working accordingly
     */
    public function testSave()
    {
        $this->car->make = 'BMW';
        $this->car->year = 2004;
        $condition = $this->car->save();
        $this->assertTrue($condition);
    }

    /**
     * Test to see all records are fetched by the find all method
     */
    public function testFindAll()
    {
        $collection = Car::findAll();
        $this->assertCount(4, $collection);
    }

    /**
     * Test to confirm the object returned has an ID that matched the
     * id passed in the find method
     */
    public function testFindOne()
    {
        $car = Car::find(1);
        $this->assertEquals('Mercedes', $car['make']);
    }

    // public function testUpdateFunction()
    // {
    //     $car = Car::find(1);
    //     $car['make'] = 'Porshe';
    //     $car->update();
    // }

    /**
     * Confirm that the delete method deletes the record
     * with the id that has been passed as a parameter
     */
    public function testDeleteWorks()
    {
        $res = Car::remove(1);
        $count = Car::findAll();
        $this->assertCount(3, $count);
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
