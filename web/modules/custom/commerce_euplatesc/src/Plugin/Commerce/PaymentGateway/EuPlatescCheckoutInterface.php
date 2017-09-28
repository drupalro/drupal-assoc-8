<?php

namespace Drupal\commerce_euplatesc\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Entity\PaymentInterface;

/**
 * Provides the interface for the EuPlatesc Checkout payment gateway.
 */
interface EuPlatescCheckoutInterface {

  /**
   * Gets the API URL.
   *
   * @return string
   *   The API URL.
   */
  public function getUrl();
 
  /**
   * SetEuPlatescCheckout request.
   *
   * Builds the data for the request.
   *
   * @param \Drupal\commerce_payment\Entity\PaymentInterface $payment
   *   The payment.
   *
   * @return array
   *   EuPlatesc data.
   *   
   */
  public function setEuPlatescCheckoutData(PaymentInterface $payment);
}
