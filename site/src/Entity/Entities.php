<?php

declare(strict_types=1);


namespace App\Entity;

use PDO;

class Entities {

  public const USER = 1;

  public const CAMPAIGN = 2;

  public const SITE = 3;

  private ?PDO $connect;

  public function __construct(PDO $connect) {
    $this->connect = $connect;
  }

  public function isEntityExist(int $entityId, ?int $entityType = NULL): bool {
    $sql = "SELECT COUNT(*) FROM entities WHERE id = :id";
    $params = [':id' => $entityId];

    if ($entityType !== NULL) {
      $sql .= " AND type = :type";
      $params[':type'] = $entityType;
    }

    $stmt = $this->connect->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() > 0;
  }

}