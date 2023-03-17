<?php

declare(strict_types=1);

use App\DBConnection;
use App\Entity\Entities;
use App\Entity\Labels;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

try {
  $connect = DBConnection::getConnection();
  $labels = new Labels($connect);

//  $labels->addLabels(Entities::CAMPAIGN, 1, ['label1', 'label4']);
//  $labels->updateLabels(Entities::CAMPAIGN, 1, ['label1', 'label4']);
   var_dump($labels->getLabels(Entities::CAMPAIGN, 1));
  $labels->deleteLabels(Entities::CAMPAIGN, 1, ['label1', 'label4']);
} catch (Exception $e) {
  echo $e->getMessage();
}


function test() {

}