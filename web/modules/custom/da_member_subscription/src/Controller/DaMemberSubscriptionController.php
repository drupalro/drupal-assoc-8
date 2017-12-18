<?php

namespace Drupal\da_member_subscription\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\da_member_subscription\Entity\SubscriptionTypeInterface;

class DaMemberSubscriptionController extends ControllerBase {

  public function add(SubscriptionTypeInterface $da_subscription_type) {
    $subscription = $this->entityTypeManager()->getStorage('da_subscription')->create([
      'type' => $da_subscription_type->id(),
    ]);

    $form = $this->entityFormBuilder()->getForm($subscription);

    return $form;
  }

}
