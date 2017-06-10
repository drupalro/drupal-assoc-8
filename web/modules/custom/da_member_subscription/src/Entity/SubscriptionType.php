<?php

namespace Drupal\da_member_subscription\Entity;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\Annotation\ConfigEntityType;

/**
 * @ConfigEntityType(
 *   id = "da_subscription_type",
 *   label = @Translation("Subscription Type"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\da_member_subscription\Form\SubscriptionTypeForm",
 *       "edit" = "Drupal\da_member_subscription\Form\SubscriptionTypeForm",
 *       "delete" = "Drupal\da_member_subscription\Form\SubscriptionTypeDeleteForm"
 *     },
 *     "list_builder" = "Drupal\da_member_subscription\SubscriptionTypeListBuilder"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "name" = "name",
 *   },
 *   bundle_of = "da_subscription",
 *   links = {
 *     "edit-form" = "/da_subscription_type/{da_subscription_type}/edit",
 *     "delete-form" = "/da_subscription_type/{da_subscription_type}/delete",
 *     "collection" = "/admin/structure/subscription-types",
 *   },
 *   config_export = {
 *     "id",
 *     "name",
 *     "price",
 *   }
 * )
 */
class SubscriptionType extends ConfigEntityBundleBase implements SubscriptionTypeInterface {

  /**
   * The id of the bundle.
   *
   * @var string
   */
  protected $id;

  /**
   * The price of the subscription type.
   *
   * @var float
   */
  protected $price;

  /**
   * The name of the subscription type.
   *
   * @var string
   */
  protected $name;

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->id;
  }

  /**
   * Returns the name of the subscription type.
   *
   * @return string
   *   The name.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Returns the price of the subscription type.
   *
   * @return float
   *   The value of the price.
   */
  public function getPrice() {
    return $this->price;
  }

  public function label() {

  }

}
