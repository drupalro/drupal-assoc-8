<?php

namespace Drupal\da_member_subscription\Form;


use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

class SubscriptionAddForm extends ContentEntityForm {

  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['uid'] = [
      '#title' => t('user id'),
      '#type' => 'textfield',
    ];

    return $form;
  }

}
