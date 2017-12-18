<?php

namespace Drupal\da_election\Controller;

use Drupal\Core\Controller\ControllerBase;

class ElectionController extends ControllerBase {

  public function getActiveElectionId() {
    $election_storage = $this->entityTypeManager()->getStorage('da_election');
    $query = $election_storage->getQuery();
    $query->condition('active', 1)
      ->condition('start_date', time(), '<')
      ->condition('end_date', time(), '>');
    $result = $query->range(0, 1)->execute();
    if (empty($result)) {
      return NULL;
    }
    return current($result);
  }
}
