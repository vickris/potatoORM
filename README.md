# POTATO ORM
[![Build Status](https://travis-ci.org/andela-cvundi/potatoORM.svg?branch=master)](https://travis-ci.org/andela-cvundi/potatoORM)
[![Coverage Status](https://coveralls.io/repos/github/andela-cvundi/potatoORM/badge.svg?branch=master)](https://coveralls.io/github/andela-cvundi/potatoORM?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/andela-cvundi/potatoORM/badges/quality-score.png?b=development)](https://scrutinizer-ci.com/g/andela-cvundi/potatoORM/?branch=development)
[![Software License][ico-license]](LICENSE.md)

POTATO ORM is a custom ORM that allows you to perform all CRUD operations on a table when working with any dtabase

## Install

Installation via Composer

``` bash
$ composer require vundi/potato-orm
```

## Usage
#### Configuration SQL databases
**NOTE:** Load you connection variables from the `.env` file in the root folder. If you do not have a `.env` file in your root folder or don't know about it, please read this [phpdotenv project](https://github.com/vlucas/phpdotenv).

Provide `Database host`, `database user`, `database password` and the `type` in the .env file. Once you provide the right values a Database connection will be established.

``` php

// Load the `.env` variables for the project
// Check the `.env.example` file in the root of the project to see the environment variables needed.
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
```

Once we have a connection, we create a model class which extends Model. Inside the model class set the entity table name and entity class
``` php
require 'vendor/autoload.php';

use Vundi\Potato\Model;
use Vundi\Potato\Exceptions\NonExistentID;
use Vundi\Potato\Exceptions\IDShouldBeNumber;

class User extends Model
{
	protected static $entity_table = 'Person';
    protected static $entity_class = 'Person';
}
```

Database interactions
``` php
// create a new user
$user = new User;
$user->name = "Kevin Karugu";
$user->age = 23;
$user->company = "Andela";
$user->save();
```

Get all users from the users table
```php
$users = User::findAll(); // Returns an array of the users found in the db
```

Get a single user from the users table
```php
$user = User::find(2); // will return user with an ID of 2
```

Edit an existing user

```php
$user = User::find(2);
$user->name = "Devy Kerr";
$user->company = "Suyabay";
$user->update();
```

Delete a user
```php
//Will remove a user from the database table
User::remove(2); // 2 represents the id of the user to be removed
```

## Testing

``` bash
$ composer test
```

## Credits

- [Christopher Vundi][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/badge/packagist-v1.0.1-brightgreen.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-author]: https://github.com/andela-cvundi