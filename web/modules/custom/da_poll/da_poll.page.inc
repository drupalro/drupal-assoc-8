<?php

/**
 * @file
 * Contains da_poll.page.inc.
 *
 * Page callback for Poll entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Poll templates.
 *
 * Default template: da_poll.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_da_poll(array &$variables) {
  // Fetch Poll Entity Object.
  $da_poll = $variables['elements']['#da_poll'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
