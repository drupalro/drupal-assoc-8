<?php

namespace Drupal\da_poll\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Poll entities.
 *
 * @ingroup da_poll
 */
interface PollInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Poll type.
   *
   * @return string
   *   The Poll type.
   */
  public function getType();

  /**
   * Gets the Poll name.
   *
   * @return string
   *   Name of the Poll.
   */
  public function getName();

  /**
   * Sets the Poll name.
   *
   * @param string $name
   *   The Poll name.
   *
   * @return \Drupal\da_poll\Entity\PollInterface
   *   The called Poll entity.
   */
  public function setName($name);

  /**
   * Gets the Poll creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Poll.
   */
  public function getCreatedTime();

  /**
   * Sets the Poll creation timestamp.
   *
   * @param int $timestamp
   *   The Poll creation timestamp.
   *
   * @return \Drupal\da_poll\Entity\PollInterface
   *   The called Poll entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Poll published status indicator.
   *
   * Unpublished Poll are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Poll is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Poll.
   *
   * @param bool $published
   *   TRUE to set this Poll to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\da_poll\Entity\PollInterface
   *   The called Poll entity.
   */
  public function setPublished($published);

  /**
   * Gets the Poll revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Poll revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\da_poll\Entity\PollInterface
   *   The called Poll entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Poll revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Poll revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\da_poll\Entity\PollInterface
   *   The called Poll entity.
   */
  public function setRevisionUserId($uid);

}
