<?php

namespace Drupal\da_election\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Define Election entity.
 *
 * @package Drupal\da_election\Entity
 *
 * @ContentEntityType(
 *   id = "da_election",
 *   label = @Translation("Election"),
 *   handlers = {
 *     "access" = "Drupal\da_election\Entity\ElectionAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\da_election\Form\ElectionForm",
 *       "edit" = "Drupal\da_election\Form\ElectionForm",
 *       "default" = "Drupal\da_election\Form\ElectionForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/election/{da_election}",
 *     "edit-form" = "/admin/election/{da_election}/edit",
 *   },
 *   base_table = "da_election",
 *   entity_keys = {
 *    "id" = "id",
 *    "uuid" = "uuid",
 *    "langcode" = "langcode"
 *   }
 * )
 */
class Election extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['start_date'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Election Candidacy Start Date'))
      ->setDescription(t('The date the candidates can start registering for this election.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['end_date'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Election Candidacy End Date'))
      ->setDescription(t('The date after the candidates can no longer register for this election.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['active'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Active Election'))
      ->setDescription(t('Check to set this election as the active election. Only one election can be active at the time.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => TRUE,
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }
}
