<?php

namespace Drupal\da_poll\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PollTypeForm.
 *
 * @package Drupal\da_poll\Form
 */
class PollTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $da_poll_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $da_poll_type->label(),
      '#description' => $this->t("Label for the Poll type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $da_poll_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\da_poll\Entity\PollType::load',
      ],
      '#disabled' => !$da_poll_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $da_poll_type = $this->entity;
    $status = $da_poll_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Poll type.', [
          '%label' => $da_poll_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Poll type.', [
          '%label' => $da_poll_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($da_poll_type->toUrl('collection'));
  }

}
