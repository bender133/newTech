<?php

declare(strict_types=1);

use App\DBConnection;
use App\EntitiesChecker;
use App\Entity\Entities;
use App\Entity\Labels;
use App\LabelEntityChecker;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

try {
  $params = ['entity_id' => 2, 'entity_type' => Entities::CAMPAIGN];
  $connect = DBConnection::getConnection();

  if (!(new EntitiesChecker($connect))->isEntityExist($params)) {
    throw new InvalidArgumentException("Сущсность с такими параметрами отсутствует");
  }

  $labels = new Labels($connect, new LabelEntityChecker($connect));

  $labels->addLabels($params['entity_type'], $params['entity_id'], [
    'label1',
    'label4',
  ]);
  var_dump($labels->getLabels($params['entity_type'], $params['entity_id']));
  $labels->updateLabels($params['entity_type'], $params['entity_id'], [
    'label1',
    'label4',
  ]);
  $labels->deleteLabels($params['entity_type'], $params['entity_id'], [
    'label1',
    'label4',
  ]);
} catch (Exception $e) {
  echo $e->getMessage();
}
