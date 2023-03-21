<?php

declare(strict_types=1);

namespace App\Tests;

use App\ConnectionInterface;
use App\DBTestConnection;
use PHPUnit\Framework\TestCase;

class Test extends TestCase {

  protected ?ConnectionInterface $connection;

  public function setUp(): void {
    $this->connection = DBTestConnection::getConnection();
    $this->connection->beginTransaction();
  }

  public function tearDown(): void {
    $this->connection->rollBack();
    $this->connection = NULL;
  }

}