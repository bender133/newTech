<?php

declare(strict_types=1);


namespace App;

use PDO;

class Connection extends PDO implements ConnectionInterface {

  private static array $connections = [];

  protected function __clone() {
  }

  public static function getConnection(): ConnectionInterface {
    $subclass = static::class;
    if (!isset(self::$connections[$subclass])) {
      self::$connections[$subclass] = new static('mysql:host=' . static::DB_HOST . ';dbname=' . static::DB_NAME, static::DB_USER, static::DB_PASS, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
    }
    return self::$connections[$subclass];
  }

}