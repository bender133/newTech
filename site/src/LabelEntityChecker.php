<?php

declare(strict_types=1);


namespace App;

class LabelEntityChecker implements EntityCheckerInterface {

  private ConnectionInterface $connection;

  public function __construct (ConnectionInterface $connection) {
      $this->connection = $connection;
  }

  public function isEntityExist(array $params): bool {
    $stmt = $this->connection->prepare("SELECT COUNT(*) FROM labels WHERE name = ? AND entity_id = ?");
    $stmt->execute([$params['label'], $params['entity_id']]);
    return (bool) $stmt->fetchColumn();
  }

}