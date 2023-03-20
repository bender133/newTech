<?php

declare(strict_types=1);


namespace App;

interface EntityCheckerInterface {

  public function isEntityExist(array $params): bool;

}