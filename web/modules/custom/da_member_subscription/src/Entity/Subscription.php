<?php

namespace Drupal\da_member_subscription\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Class Subscription.
 *
 * @ContentEntityType(
 *   id = "da_subscription",
 *   label = @Translation("Drupal Association Subscription"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "form" = {
 *       "default" = "Drupal\da_member_subscription\Form\SubscriptionAddForm"
 *     },
 *   },
 *   bundle_entity_type = "da_subscription_type",
 *   base_table = "da_subscription",
 *   entity_keys = {
 *     "id" = "id",
 *     "type" = "type",
 *     "uid" = "uid",
 *     "uuid" = "uuid",
 *     "created" = "created"
 *   }
 * )
 */
class Subscription extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = [];

    if ($entity_type->hasKey('id')) {
      $fields[$entity_type->getKey('id')] = BaseFieldDefinition::create('integer')
        ->setLabel(new TranslatableMarkup('ID'))
        ->setReadOnly(TRUE)
        ->setSetting('unsigned', TRUE);
    }
    if ($entity_type->hasKey('uuid')) {
      $fields[$entity_type->getKey('uuid')] = BaseFieldDefinition::create('uuid')
        ->setLabel(new TranslatableMarkup('UUID'))
        ->setReadOnly(TRUE);
    }

    if ($entity_type->hasKey('uid')) {
      $fields[$entity_type->getKey('uid')] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(new TranslatableMarkup('UID'))
        ->setReadOnly(TRUE)
        ->setSetting('target_type', 'user');
    }

    if ($entity_type->hasKey('type')) {
      if ($bundle_entity_type_id = $entity_type->getBundleEntityType()) {
        $fields[$entity_type->getKey('type')] = BaseFieldDefinition::create('entity_reference')
          ->setLabel($entity_type->getBundleLabel())
          ->setSetting('target_type', $bundle_entity_type_id)
          ->setRequired(TRUE)
          ->setReadOnly(TRUE);
      }
    }

    if ($entity_type->hasKey('created')) {
      $fields['created'] = BaseFieldDefinition::create('created')
        ->setLabel(t('Authored on'))
        ->setDescription(t('The time that the node was created.'))
        ->setDisplayOptions('view', [
          'label' => 'hidden',
          'type' => 'timestamp',
          'weight' => 0,
        ]);
    }

    return $fields;
  }

}
