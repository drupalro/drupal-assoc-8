<?php

namespace Drupal\da_election\Entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

class ElectionAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view election entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'administer election entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'administer election entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}