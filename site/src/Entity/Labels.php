<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;
use PDO;

class Labels {

  private ?PDO $connect;

  private Entities $entities;

  public function __construct(PDO $connect) {
    $this->connect = $connect;
    $this->entities = new Entities($connect);
  }

  public function getLabels(int $entityType, int $entityId): array {

    if (!$this->entities->isEntityExist($entityId, $entityType)) {
      throw new InvalidArgumentException("Сущность с ID $entityId и типом $entityType не существует");
    }

    $stmt = $this->connect->prepare("SELECT * FROM labels WHERE entity_id = ?");
    $stmt->execute([$entityId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * @throws \Exception
   */
  public function updateLabels(int $entityType, int $entityId, array $labels = []): void {
    try {
      $this->connect->beginTransaction();

      foreach ($labels as $label) {
        if (!$this->isLabelExist($label, $entityId)) {
          throw new InvalidArgumentException("Отсутствует $label");
        }
      }

      $this->deleteLabels($entityType, $entityId, $labels);
      $this->addLabels($entityType, $entityId, $labels);

      $this->connect->commit();
    } catch (\Exception $e) {
      $this->connect->rollBack();
      throw $e;
    }
  }

  public function deleteLabels(int $entityType, int $entityId, array $labels): void {
    if (empty($labels)) {
      throw new InvalidArgumentException('Список лейблов пуст');
    }

    if (!$this->entities->isEntityExist($entityId, $entityType)) {
      throw new InvalidArgumentException('Сущность не существует');
    }

    foreach ($labels as $label) {
      if (!$this->deleteLabel($entityId, $label)) {
        throw new InvalidArgumentException("Отсутствует $label");
      }
    }
  }

  public function deleteLabel(int $entityId, string $label): bool {
    $stmt = $this->connect->prepare("DELETE FROM labels WHERE entity_id = :entity_id AND name = :name");
    return $this->isLabelExist($label, $entityId) && $stmt->execute([
        ':entity_id' => $entityId,
        ':name' => $label,
      ]);
  }

  public function addLabels(int $entityType, int $entityId, array $labels): void {

    if (empty($labels)) {
      throw new InvalidArgumentException('пустой список');
    }

    if (!$this->entities->isEntityExist($entityId, $entityType)) {
      throw new InvalidArgumentException('Нет такой сущности');
    }

    $stmt = $this->connect->prepare('INSERT INTO labels (name, entity_id) VALUES (:name, :entity_id)');
    foreach ($labels as $label) {
      $stmt->execute([':name' => $label, ':entity_id' => $entityId]);
    }
  }

  public function isLabelExist(string $label, int $entityId): bool {
    $stmt = $this->connect->prepare("SELECT COUNT(*) FROM labels WHERE name = ? AND entity_id = ?");
    $stmt->execute([$label, $entityId]);
    return (bool) $stmt->fetchColumn();
  }

}