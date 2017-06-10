<?php

namespace Drupal\da_calendar\Plugin\views\area;


use Drupal\views\Plugin\views\area\AreaPluginBase;

/**
 * Alter the HTTP response status code used by the view.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("legal_framework")
 */
class LegalFrameWorkAnchors extends AreaPluginBase {

  public function render($empty = FALSE) {
    $test = $this->view->result;

    return [
      '#theme' => 'item_list',
      '#items' => [
        'lol1',
        'lol2',
      ],
    ];
  }

}
