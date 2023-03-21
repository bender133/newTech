<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Labels;
use App\EntityChecker;
use App\LabelChecker;

class EntityCheckerTest extends Test {

  public function testCheckEntity(): void {
    $labelChecker = new LabelChecker($this->connection);
    $entityChecker = new EntityChecker($this->connection);
    (new Labels($this->connection, $entityChecker))->addLabels(1, ['test']);
    self::assertTrue($labelChecker->isEntityExist([
      'label' => 'test',
      'entity_id' => 1,
    ]));
    self::assertTrue($entityChecker->isEntityExist([
      'entity_type' => 2,
      'entity_id' => 1,
    ]));
    self::assertFalse($labelChecker->isEntityExist([
      'label' => 'rrr',
      'entity_id' => 1,
    ]));
    self::assertFalse($entityChecker->isEntityExist([
      'entity_type' => 1,
      'entity_id' => 1,
    ]));
    self::assertFalse($entityChecker->isEntityExist([
      'entity_type' => 2,
      'entity_id' => 2,
    ]));
  }

}