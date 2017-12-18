<?php

namespace Drupal\da_calendar\Plugin\Block;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'fullcalendar' block.
 *
 * @Block(
 *   id = "fullcalendarblock",
 *   admin_label = @Translation("FullCalendar Block"),
 * )
 */
class CalendarBlock extends BlockBase {

  public function build() {
    $block = [
      '#prefix' => '<div id="calendar">',
      '#suffix' => '<div>'
    ];
    $block['#attached']['library'][] = 'da_calendar/fullcalendar';

    return $block;
  }

}
