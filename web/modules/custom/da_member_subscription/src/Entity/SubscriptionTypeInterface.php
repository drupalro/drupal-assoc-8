<?php

namespace Drupal\da_member_subscription\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

interface SubscriptionTypeInterface extends ConfigEntityInterface {

  /**
   * @return mixed
   */
  public function getName();

  /**
   * @return mixed
   */
  public function getPrice();

}
