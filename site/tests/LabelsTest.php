<?php

use App\EntityCheckerInterface;
use App\ConnectionInterface;
use App\Entity\Entities;
use App\Entity\Labels;
use App\DBTestConnection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LabelsTest extends TestCase {

  private ?ConnectionInterface $connection;

  private ?array $params;

  private ?Labels $label;

  private ?MockObject $checker;

  public function setUp(): void {


    $this->connection = DBTestConnection::getConnection();
    $this->connection->beginTransaction();
    $this->params = ['entity_id' => 1, 'entity_type' => Entities::CAMPAIGN];
    $this->checker = $this->createMock(EntityCheckerInterface::class);
    $this->label = new Labels($this->connection, $this->checker);
  }

  public function tearDown(): void {
    $this->connection->rollBack();
    $this->connection = NULL;
    $this->params = NULL;
    $this->checker = NULL;
    $this->label = NULL;
  }

  public function testLabelSuccess(): void {
    $this->checker->method('isEntityExist')->willReturn(TRUE);
    $data = ['label1', 'label4'];
    $this->label->addLabels($this->params['entity_id'], $data);
    $label1 = $this->label->getLabels($this->params['entity_id']);
    $this->label->updateLabels($this->params['entity_id'], $data);
    $label2 = $this->label->getLabels($this->params['entity_id']);
    $this->label->deleteLabels($this->params['entity_id'], $data);
    self::assertNotEmpty($label1);
    self::assertNotEmpty($label2);
    self::assertNotEquals($label1, $label2);
    self::assertEmpty($this->label->getLabels($this->params['entity_id']));
  }

  public function testAddLabelsFailure(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('пустой список');
    $this->label->addLabels($this->params['entity_id'], []);
  }

}
