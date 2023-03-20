<?php

declare(strict_types=1);

namespace App\Entity;

use App\ConnectionInterface;
use App\EntityCheckerInterface;
use InvalidArgumentException;
use PDO;

class Labels {

  private ?ConnectionInterface $connect;

  /**
   * @var \App\EntityCheckerInterface
   */
  private EntityCheckerInterface $checker;

  public function __construct(ConnectionInterface $connect, EntityCheckerInterface $checker) {
    $this->connect = $connect;
    $this->checker = $checker;
  }

  public function getLabels(int $entityId): array {
    $stmt = $this->connect->prepare("SELECT * FROM labels WHERE entity_id = ?");
    $stmt->execute([$entityId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * @throws \Exception
   */
  public function updateLabels(int $entityId, array $labels = []): void {
    foreach ($labels as $label) {
      if (!$this->checker->isEntityExist([
        'label' => $label,
        'entity_id' => $entityId,
      ])) {
        throw new InvalidArgumentException("Отсутствует $label");
      }

      $this->deleteLabels($entityId, $labels);
      $this->addLabels($entityId, $labels);
    }
  }

  public function deleteLabels(int $entityId, array $labels): void {
    if (empty($labels)) {
      throw new InvalidArgumentException('Список лейблов пуст');
    }

    foreach ($labels as $label) {
      if (!$this->deleteLabel($entityId, $label)) {
        throw new InvalidArgumentException("Отсутствует $label");
      }
    }
  }

  private function deleteLabel(int $entityId, string $label): bool {
    $stmt = $this->connect->prepare("DELETE FROM labels WHERE entity_id = :entity_id AND name = :name");
    return $this->checker->isEntityExist([
        'label' => $label,
        'entity_id' => $entityId,
      ])
      && $stmt->execute([
        ':entity_id' => $entityId,
        ':name' => $label,
      ]);
  }

  public function addLabels(int $entityId, array $labels): void {

    if (empty($labels)) {
      throw new InvalidArgumentException('пустой список');
    }

    $stmt = $this->connect->prepare('INSERT INTO labels (name, entity_id) VALUES (:name, :entity_id)');
    foreach ($labels as $label) {
      $stmt->execute([':name' => $label, ':entity_id' => $entityId]);
    }
  }

}