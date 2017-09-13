<?php

namespace Drupal\da_poll\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Poll entities.
 */
class PollViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
