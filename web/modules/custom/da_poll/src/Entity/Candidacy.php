<?php

namespace Drupal\da_poll\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Define Candidacy entity.
 *
 * @package Drupal\da_poll\Entity
 *
 * @ContentEntityType(
 *   id = "da_candidacy",
 *   label = @Translation("Election Candidacy"),
 *   handlers = {
 *     "view_builder" = "Drupal\da_poll\Entity\CandidacyViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\da_poll\Form\CandidacyForm",
 *       "edit" = "Drupal\da_poll\Form\CandidacyForm",
 *       "default" = "Drupal\da_poll\Form\CandidacyForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/election/{da_poll}",
 *     "edit-form" = "/election/{da_poll}/edit",
 *   },
 *   base_table = "da_candidacy",
 *   entity_keys = {
 *    "id" = "id",
 *    "uuid" = "uuid",
 *    "langcode" = "langcode",
 *    "uid" = "uid"
 *   }
 * )
 */
class Candidacy extends ContentEntityBase {
  use StringTranslationTrait;

  /**
   * Sets available positions.
   *
   * @return array
   *   An array with available positions.
   */
  protected static function getAvailablePositions() {
    $positions = [
      'board_memeber' => t('Board Member'),
      'president' => t('President'),
    ];

    return $positions;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setDescription(t('The user which creates a candidacy.'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ]);

    $fields['election'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Election'))
      ->setDescription(t('The election for which this candidacy is for.'))
      ->setSetting('target_type', 'da_poll')
      ->setDefaultValueCallback('Drupal\da_poll\Entity\Candidacy::getCurrentElectionId')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ]);

    $fields['position'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Position'))
      ->setDescription(t('The the position for which you candidate.'))
      ->setSetting('allowed_values', Candidacy::getAvailablePositions())
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['profile'] = BaseFieldDefinition::create('text_long')
      ->setLabel('Profile')
      ->setDescription('The candidacy profile')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'text_textfield',
        'weight' => 1,
      ]);

    return $fields;
  }

  /**
   * Returns current election id.
   *
   * @return mixed
   *   The id of current election or NULL.
   */
  public static function getCurrentElectionId() {
    return [\Drupal::service('da_poll.service')->getActiveElectionId()];
  }

}
