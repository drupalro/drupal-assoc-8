<?php

namespace Drupal\da_member_subscription\Service;

use Drupal\Core\Session\AccountProxyInterface;

class MemberSubscriber implements MemberSubscriberInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var AccountProxyInterface
   */
  protected $currentUser;

  /**
   * MemberSubscriber constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   */
  public function __construct(\Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager, \Drupal\Core\Session\AccountProxyInterface $currentUser) {
    $this->entityTypeManager = $entityTypeManager;
    $this->currentUser = $currentUser;
  }


  public function createSubscription($subscriptionType) {
    $entity = $this->entityTypeManager->getStorage('da_subscription')->create(
      [
        'uid' => $this->currentUser->id(),
        'type' => $subscriptionType,
      ]
    );

    $entity->save();
  }

  public function renewSubscription() {

  }

}
