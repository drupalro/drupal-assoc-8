<?php

namespace Drupal\da_member_subscription\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SubscriptionTypeForm.
 *
 * @package Drupal\da_member_subscription\Form
 */
class SubscriptionTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['name'] = [
      '#title' => t('The name of the Subscription Type'),
      '#type' => 'textfield',
      '#default_value' => $this->entity->getName(),
    ];

    $form['id'] = [
      '#title' => t('The machine name of the Subscription Type'),
      '#type' => 'textfield',
      '#default_value' => $this->entity->id(),
    ];

    $form['price'] = [
      '#title' => t('The price of the Subscription Type'),
      '#type' => 'number',
      '#step' => 0.01,
      '#min' => 0,
      '#default_value' => $this->entity->getPrice(),
    ];

    return $form;
  }

}
