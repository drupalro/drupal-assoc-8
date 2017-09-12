<?php

namespace Drupal\da_poll;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Poll entity.
 *
 * @see \Drupal\da_poll\Entity\Poll.
 */
class PollAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\da_poll\Entity\PollInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished poll entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published poll entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit poll entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete poll entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add poll entities');
  }

}
