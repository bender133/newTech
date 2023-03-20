<?php

declare(strict_types=1);


namespace App;

class EntityChecker implements EntityCheckerInterface {

  private ConnectionInterface $connection;

  public function __construct(ConnectionInterface $connection) {
    $this->connection = $connection;
  }

  public function isEntityExist(array $params): bool {
    $sql = "SELECT COUNT(*) FROM entities WHERE id = :id";
    $val = [':id' => $params['entity_id']];

    if ($params['entity_type'] !== NULL) {
      $sql .= " AND type = :type";
      $val[':type'] = $params['entity_type'];
    }

    $stmt = $this->connection->prepare($sql);
    $stmt->execute($val);

    return $stmt->fetchColumn() > 0;
  }

}