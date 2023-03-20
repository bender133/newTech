<?php

declare(strict_types=1);

use App\DBProdConnection;
use App\EntityChecker;
use App\Entity\Entities;
use App\Entity\Labels;
use App\LabelChecker;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

try {
  $params = ['entity_id' => 1, 'entity_type' => Entities::CAMPAIGN];
  $connect = DBProdConnection::getConnection();

  if (!(new EntityChecker($connect))->isEntityExist($params)) {
    throw new InvalidArgumentException("Сущсность с такими параметрами отсутствует");
  }

  $labels = new Labels($connect, new LabelChecker($connect));

  $labels->addLabels($params['entity_id'], [
    'label1',
    'label4',
  ]);
  var_dump($labels->getLabels($params['entity_id']));
  $labels->updateLabels($params['entity_id'], [
    'label1',
    'label4',
  ]);
  $labels->deleteLabels($params['entity_id'], [
    'label1',
    'label4',
  ]);
} catch (Exception $e) {
  echo $e->getMessage();
}
