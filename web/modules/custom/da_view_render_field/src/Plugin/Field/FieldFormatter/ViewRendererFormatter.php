<?php

namespace Drupal\da_view_render_field\Plugin\Field\FieldFormatter;

use Drupal\Console\Bootstrap\Drupal;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Display implementation of 'field_view_renderer'.
 *
 * @FieldFormatter(
 *   id = "field_view_renderer_display",
 *   module = "da_view_render_field",
 *   label = @Translation("View Display"),
 *   field_types = {
 *     "field_view_renderer"
 *   }
 * )
 */
class ViewRendererFormatter extends FormatterBase {

  /**
   * @inheritdoc
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $view = $this->getView($items);
    return array('#markup' => \Drupal::service('renderer')->render($view));
  }

  /**
   * Get a view based on the view_id value.
   *
   * @return array|NULL
   *  A renderable array containing the view output or NULL if the view was not
   *  found.
   */
  private function getView(FieldItemListInterface $items) {
    $view_elements = array();
    foreach ($items as $item) {
      if (isset($item->view_id)) {
        $view_elements = explode(':', $item->view_id);
        break;
      }
    }
    return !empty($view_elements) ? views_embed_view($view_elements[0], $view_elements[1]) : NULL;
  }
}
