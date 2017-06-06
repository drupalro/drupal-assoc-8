<?php

namespace Drupal\da_view_reference_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * View reference plugin definition.
 *
 * @FieldType(
 *   id = "field_view_reference",
 *   label = @Translation("View Reference"),
 *   module = "da_view_reference_field",
 *   description = @Translation("Render a given view"),
 *   default_widget = "field_view_reference_widget",
 *   default_formatter = "field_view_reference_display"
 * )
 */
class ViewReference extends FieldItemBase {

  /**
   * @inheritdoc
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'view_id' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ),
      ),
    );
  }

  /**
   * @inheritdoc
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['view_id'] = DataDefinition::create('string')
      ->setLabel(t('View ID'))
      ->addConstraint('valid_view_display');

    return $properties;
  }
}
