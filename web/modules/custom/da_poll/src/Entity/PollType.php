<?php

namespace Drupal\da_poll\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Poll type entity.
 *
 * @ConfigEntityType(
 *   id = "da_poll_type",
 *   label = @Translation("Poll type"),
 *   handlers = {
 *     "list_builder" = "Drupal\da_poll\PollTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\da_poll\Form\PollTypeForm",
 *       "edit" = "Drupal\da_poll\Form\PollTypeForm",
 *       "delete" = "Drupal\da_poll\Form\PollTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\da_poll\PollTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "da_poll_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "da_poll",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/da_poll_type/{da_poll_type}",
 *     "add-form" = "/admin/structure/da_poll_type/add",
 *     "edit-form" = "/admin/structure/da_poll_type/{da_poll_type}/edit",
 *     "delete-form" = "/admin/structure/da_poll_type/{da_poll_type}/delete",
 *     "collection" = "/admin/structure/da_poll_type"
 *   }
 * )
 */
class PollType extends ConfigEntityBundleBase implements PollTypeInterface {

  /**
   * The Poll type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Poll type label.
   *
   * @var string
   */
  protected $label;

}
