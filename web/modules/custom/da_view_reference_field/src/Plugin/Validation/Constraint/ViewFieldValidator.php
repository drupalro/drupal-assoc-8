<?php

namespace Drupal\da_view_reference_field\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validate 'field_view_reference' fields.
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
      $this->validateInput($value, $constraint);
    }
  }

  /**
   * Validate the field input.
   */
  private function validateInput($value, Constraint $constraint) {
    $field_components = explode(':', $value);
    if (count($field_components) != 2) {
      $this->context->addViolation($constraint->invalidFormat, array('%value' => $value));
    }
    else {
      $this->validateView($field_components[0], $field_components[1], $constraint);
    }
  }

  /**
   * Validate the view if the input has the right format.
   */
  private function validateView($view_name, $display_name, Constraint $constraint) {
    $view = \Drupal::service('entity.manager')->getStorage('view')->load($view_name);
    if (!$view) {
      $this->context->addViolation($constraint->invalidViewName, array('%view_name' => $view_name));
    }
    elseif (!$view->getDisplay($display_name)) {
      $this->context->addViolation($constraint->invalidDisplay, array('%display_name' => $display_name));
    }
  }
}
