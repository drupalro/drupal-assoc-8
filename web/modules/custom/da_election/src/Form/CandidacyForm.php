<?php

namespace Drupal\da_election\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form handler for the election candidacy forms.
 */
class CandidacyForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $profile = $this->entity->get('profile')->getValue();
    $formats = array_keys(filter_formats($this->currentUser()));

    $form['profile'] = [
      '#type' => 'text_format',
      '#title' => 'Candidacy Profile',
      '#default_value' => isset($profile[0]['value']) ? $profile[0]['value'] : '',
      '#format' => isset($profile[0]['format']) ? $profile[0]['format'] : current($formats),
      '#allowed_formats' => $formats,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->entity->save();
    $form_state->setRedirect('entity.da_candidacy.canonical', ['da_candidacy' => $this->entity->id()]);
  }
}
