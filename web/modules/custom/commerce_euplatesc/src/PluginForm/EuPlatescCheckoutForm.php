<?php

namespace Drupal\commerce_euplatesc\PluginForm;

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;

class EuPlatescCheckoutForm extends BasePaymentOffsiteForm {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;

    /** @var \Drupal\commerce_euplatesc\Plugin\Commerce\PaymentGateway $payment_gateway_plugin */
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
    $redirect_url = $payment_gateway_plugin->getUrl();
    // Get plugin configuration.
    $plugin_config = $payment_gateway_plugin->getConfiguration();

    $euplatesc_data = $payment_gateway_plugin->setEuPlatescCheckoutData($payment);
    foreach ($euplatesc_data as $name => $value) {
      if (!empty($value)) {
        $data[$name] = $value;
      }
    }

    return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, $plugin_config['redirect_method']);
  }
}
