<?php

namespace Drupal\da_member_subscription\Entity;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\Annotation\ConfigEntityType;

/**
 * @ConfigEntityType(
 *   id = "subscription_type",
 *   label = @Translation("Subscription Type"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\da_member_subscription\SubscriptionTypeForm",
 *       "edit" = "Drupal\da_member_subscription\SubscriptionTypeForm",
 *       "delete" = "Drupal\da_member_subscription\SubscriptionTypeDeleteForm"
 *     },
 *   }
 * )
 */
class SubscriptionType extends ConfigEntityBundleBase {

}
