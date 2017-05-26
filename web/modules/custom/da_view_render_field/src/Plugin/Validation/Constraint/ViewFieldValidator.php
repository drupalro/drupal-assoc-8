<?php

namespace Drupal\da_view_render_field\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validate 'field_view_renderer' fields.
 */
class ViewFieldValidator extends ConstraintValidator {

  /**
   * @inheritdoc
   */
  public function validate($value, Constraint $constraint) {
    if (empty($value)) {
      $this->context->addViolation($constraint->emptyViewField);
    }
    else {
      $field_components = explode(':', $value);
      if (count($field_components) != 2) {
        $this->context->addViolation($constraint->invalidFormat, array('%value' => $value));
      }
      else {
        $view = \Drupal::service('entity.manager')->getStorage('view')->load($field_components[0]);
        if (!$view) {
          $this->context->addViolation($constraint->invalidViewName, array('%view_name' => $field_components[0]));
        }
        elseif (!$view->getDisplay($field_components[1])) {
            $this->context->addViolation($constraint->invalidDisplay, array('%display_name' => $field_components[1]));
        }
      }
    }
  }
}
