<?php

namespace Drupal\da_poll;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface PollStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Poll revision IDs for a specific Poll.
   *
   * @param \Drupal\da_poll\Entity\PollInterface $entity
   *   The Poll entity.
   *
   * @return int[]
   *   Poll revision IDs (in ascending order).
   */
  public function revisionIds(PollInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Poll author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Poll revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\da_poll\Entity\PollInterface $entity
   *   The Poll entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PollInterface $entity);

  /**
   * Unsets the language for all Poll with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
