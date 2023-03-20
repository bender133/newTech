<?php

declare(strict_types=1);

namespace App;

use PDO;

class  DBConnection extends PDO implements ConnectionInterface {

  private static PDO $connection;

  private const DB_HOST = '134.0.0.3';

  private const DB_NAME = 'db';

  private const DB_PASS = 'root';

  private const DB_USER = 'root';

  protected function __clone() {
  }

  public static function getConnection(): ConnectionInterface {
    if (!isset(self::$connection)) {

      self::$connection = new self('mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME, self::DB_USER, self::DB_PASS, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
    }
    return self::$connection;
  }

}
