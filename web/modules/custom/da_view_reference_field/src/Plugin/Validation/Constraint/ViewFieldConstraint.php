<?php

namespace Drupal\da_view_reference_field\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks if the given view & display are valid.
 *
 * @Constraint(
 *   id = "valid_view_display",
 *   label = @Translation("Valid View", context = "Validation"),
 * )
 */
class ViewFieldConstraint extends Constraint {

  public $emptyViewField = 'Field is empty.';

  public $invalidFormat = '%value is not a valid format. Use view_id:display_name instead.';

  public $invalidViewName = '%view_name is not a valid view.';

  public $invalidDisplay = '%display_name is not a valid display.';

  /**
   * @inheritdoc
   */
  public function validatedBy() {
    return '\Drupal\da_view_reference_field\Plugin\Validation\Constraint\ViewFieldValidator';
  }
}
