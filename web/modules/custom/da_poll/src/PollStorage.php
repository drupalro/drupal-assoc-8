<?php

namespace Drupal\da_poll;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\da_poll\Entity\PollInterface;

/**
 * Defines the storage handler class for Poll entities.
 *
 * This extends the base storage class, adding required special handling for
 * Poll entities.
 *
 * @ingroup da_poll
 */
class PollStorage extends SqlContentEntityStorage implements PollStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(PollInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {da_poll_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {da_poll_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(PollInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {da_poll_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('da_poll_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
