<?php

namespace Drupal\da_view_render_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * View renderer plugin definition.
 *
 * @FieldType(
 *   id = "field_view_renderer",
 *   label = @Translation("View Renderer"),
 *   module = "da_view_render_field",
 *   description = @Translation("Render a given view"),
 *   default_widget = "field_view_renderer_widget",
 *   default_formatter = "field_view_renderer_display"
 * )
 */
class ViewRenderer extends FieldItemBase {

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
      ->setLabel(t('View ID'));

    return $properties;
  }
}
