<?php

namespace Vundi\Potato\Test;

use PHPUnit_Framework_TestCase;
use Vundi\Potato\Database;

class DBConnectionTest extends PHPUnit_Framework_TestCase
{
    public function testConnection()
    {
        new Database();
        $DB = Database::$db_handler;
        $this->assertInstanceOf('PDO', $DB);
    }
}
