<?php

namespace Vundi\Potato\Test;

use PHPUnit_Framework_TestCase;
use PDO;

class DBConnectionTest extends PHPUnit_Framework_TestCase
{
    public function testConnection()
    {
        $connection = [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'dbname' => 'potato',
            'dbtype' => 'mysql',
        ];

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );

        $dsn = $connection['dbtype'].':host='.$connection['host'].';dbname='.$connection['dbname'];

        $DB = new PDO($dsn, $connection['user'], $connection['pass'], $options);

        $this->assertInstanceOf('PDO', $DB);
    }

    /**
     * [testErrorIsThrownIfUserDoesNotProvideConnectionDetails description].
     *
     * @expectedException \PDOException
     */
    public function testErrorIsThrownIfUserDoesNotProvideConnectionDetails()
    {
        $dsn = ' ';
        $DB = new PDO($dsn);
    }
}
