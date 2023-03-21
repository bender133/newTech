<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Entities;
use App\Entity\Labels;
use App\EntityCheckerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use InvalidArgumentException;

class LabelsTest extends Test {

  private ?array $params;

  private ?Labels $label;

  private ?MockObject $checker;

  public function setUp(): void {
    parent::setUp();
    $this->params = ['entity_id' => 1, 'entity_type' => Entities::CAMPAIGN];
    $this->checker = $this->createMock(EntityCheckerInterface::class);
    $this->label = new Labels($this->connection, $this->checker);
  }

  public function tearDown(): void {
    parent::tearDown();
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

  /**
   * @dataProvider labelsDataProvider
   *
   * @param array $labels
   * @param string $exception
   * @param string $message
   */
  public function testDeleteLabelsFailure(array $labels, string $exception, string $message): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage($message);
    $this->label->deleteLabels($this->params['entity_id'], $labels);
  }

  public function testUpdateLabelsFailure(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Отсутствует dkdkdk');
    $this->label->updateLabels($this->params['entity_id'], ['dkdkdk']);
  }

  public function labelsDataProvider(): \Generator {
    yield [
      ['dkdkdk'],
      InvalidArgumentException::class,
      'Отсутствует dkdkdk',
    ];
    yield [
      [],
      InvalidArgumentException::class,
      'Список лейблов пуст',
    ];
  }

}
