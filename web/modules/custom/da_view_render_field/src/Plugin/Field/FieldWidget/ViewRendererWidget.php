<?php

namespace Drupal\da_view_render_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Widget implementation of 'field_view_renderer'.
 *
 * @FieldWidget(
 *   id = "field_view_renderer_widget",
 *   module = "da_view_render_field",
 *   label = @Translation("View name and display"),
 *   description = @Translation("Format: 'view_machine_name:view_display'"),
 *   field_types = {
 *     "field_view_renderer"
 *   }
 * )
 */
class ViewRendererWidget extends WidgetBase {

  /**
   * @inheritdoc
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->view_id) ? $items[$delta]->view_id : '';
    $element += array(
      '#type' => 'textfield',
      '#default_value' => $value,
    );
    return array('view_id' => $element);
  }
}
