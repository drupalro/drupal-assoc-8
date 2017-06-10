<?php

namespace Drupal\commerce_euplatesc\Plugin\Commerce\PaymentGateway;


use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\commerce_price\Calculator;

/**
 * Provides the EuPlatesc Checkout payment gateway plugin.
 *
 * @CommercePaymentGateway(
 *   id = "euplatesc_checkout",
 *   label = @Translation("EuPlatesc Checkout"),
 *   display_label = @Translation("EuPlatesc"),
 *    forms = {
 *     "offsite-payment" = "Drupal\commerce_euplatesc\PluginForm\EuPlatescCheckoutForm",
 *   },
 *   payment_method_types = {"credit_card"},
 *   credit_card_types = {
 *     "mastercard", "visa", "maestro",
 *   },
 * )
 */
class EuPlatescCheckout extends OffsitePaymentGatewayBase implements EuPlatescCheckoutInterface {

  /**
   * The price rounder.
   *
   * @var \Drupal\commerce_price\RounderInterface
   */
  protected $rounder;

  /**
   * The time.
   *
   * @var \Drupal\commerce\TimeInterface
   */
  protected $time;

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'merchant_id' => '',
      'secret_key' => '',
      'redirect_method' => 'post',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['merchant_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Merchant ID'),
      '#description' => t('The merchant id from the EuPlatesc.ro provider.'),
      '#default_value' => $this->configuration['merchant_id'],
      '#required' => TRUE,
    ];
    $form['secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Secret key'),
      '#description' => t('The secret key id from the EuPlatesc.ro provider.'),
      '#default_value' => $this->configuration['secret_key'],
      '#required' => TRUE,
    ]; 

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      $this->configuration['merchant_id'] = $values['merchant_id'];
      $this->configuration['secret_key'] = $values['secret_key'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onReturn(OrderInterface $order, Request $request) {
    $data = $this->getRequestData($request);

    $configuration = $this->getConfiguration();
    $data['fp_hash'] = strtoupper($this->hashData($data, $configuration['secret_key']));
    $fp_hash = addslashes(trim($request->query->get('fp_hash')));

    if ($data['fp_hash'] !== $fp_hash) {
      throw new PaymentGatewayException('Invalid signature');
    }

    $payment = $this->createPaymentStorage($order, $request);

    if ($request->query->get('action') == "0") {
      $order->setData('state', 'completed');
      $payment->state = 'authorization';

      drupal_set_message(t('The payment was made successfully.'), 'status');
    }
    else {
      $payment->state = 'authorization_voided';

      drupal_set_message(t('Transaction failed: @message', array('@message' => $request->query->get['message'])), 'warning');
    }

    $order->save();
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function getUrl() {
    return 'https://secure.euplatesc.ro/tdsprocess/tranzactd.php';
  }

  /**
   * {@inheritdoc}
   */
  public function setEuPlatescCheckoutData(PaymentInterface $payment) {
    $order = $payment->getOrder();

    $amount = $payment->getAmount();
    $configuration = $this->getConfiguration();

    // Order description.
    $order_desc = 'Order #' . $order->id() . ': ';

    foreach ($order->getItems() as $item) {
      $product_sku = $item->getPurchasedEntity()->getSku();
      $order_desc .= $item->getTitle()  . ' [' . $product_sku . ']';
      $order_desc .= ', ';
    }

    // Remove the last comma.
    $order_desc = rtrim($order_desc, ', ');

    // Curent timestamp.
    $timestamp = gmdate('YmdHis');
    $nonce = md5(microtime() . mt_rand());

    // Build a name-value pair array for this transaction.
    // The data which should be signed to be transported to EuPlatesc.ro.
    $data = [
      'amount' => Calculator::round($amount->getNumber(), 2),
      'curr' => $amount->getCurrencyCode(),
      'invoice_id' => $order->id(),
      'order_desc' => $order_desc,
      'merch_id' => $configuration['merchant_id'],
      'timestamp' => $timestamp,
      'nonce' => $nonce,
    ];

    $address = $order->getBillingProfile()->get('address')->first();

    // The hidden data wich should be transported to EuPlatesc.ro.
    $nvp_data = [
      'fname' => $address->getGivenName(),
      'lname' => $address->getFamilyName(),
      'country' => $address->getCountryCode(),
      'city' => $address->getLocality(),
      'email' => $order->getEmail(),
      'amount' => Calculator::round($amount->getNumber(), 2),
      'curr' => $amount->getCurrencyCode(),
      'invoice_id' => $order->id(),
      'order_desc' => $order_desc,
      'merch_id' => $configuration['merchant_id'],
      'timestamp' => $timestamp,
      'nonce' => $nonce,
      'fp_hash' => strtoupper($this->hashData($data, $configuration['secret_key']))
    ];

    return $nvp_data;
  }

  /**
   * Get data from Request object.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   */
  public function getRequestData(Request $request) {
    return [
       'amount' => addslashes(trim($request->query->get('amount'))),
       'curr' => addslashes(trim($request->query->get('curr'))),
       'invoice_id' => addslashes(trim($request->query->get('invoice_id'))),
       // A unique id provided by EuPlatesc.ro.
       'ep_id' => addslashes(trim($request->query->get('ep_id'))),
       'merch_id' => addslashes(trim($request->query->get('merch_id'))),
       // For the transaction to be ok, the action should be 0.
       'action' => addslashes(trim($request->query->get('action'))),
       // The transaction response message.
       'message' => addslashes(trim($request->query->get('message'))),
       // If the transaction action is different 0, the approval value is empty.
       'approval' => addslashes(trim($request->query->get('approval'))),
       'timestamp' => addslashes(trim($request->query->get('timestamp'))),
       'nonce' => addslashes(trim($request->query->get('nonce'))),
     ];
  }

  /**
   * Create a PaymentStorage object.
   *  
   * @param \Drupal\commerce_order\Entity\OrderInterface $order
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @return type
   */
  public function createPaymentStorage(OrderInterface $order, Request $request) {
    $payment_storage = $this->entityTypeManager->getStorage('commerce_payment');
    $request_time = $this->time->getRequestTime();
    return $payment_storage->create([
      'state' => 'authorization',
      'amount' => $order->getTotalPrice(),
      'payment_gateway' => $this->entityId,
      'order_id' => $order->id(),
      'test' => $this->getMode() == 'test',
      'remote_id' => $request->query->get('ep_id'),
      'remote_state' => $request->query->get('message'),
      'authorized' => $request_time,
    ]);
  }

  /**
   * Custom function from EuPlatesc documentation.
   * Fore more details, please read the documentation from module.
   *
   * @param array $data
   * @param string $key
   * @return string.
   */
  public static function hashData($data, $key) {
    $str = NULL;

    foreach ($data as $d) {
      if ($d === NULL || strlen($d) == 0) {
        // The NULL values will be replaced with - .
        $str .= '-';
      }
      else {
        $str .= strlen($d) . $d;
      }
    }

    // We convert the secret code into a binary string.
    $key = pack('H*', $key);

    return self::hashSHA1($str, $key);
  }
  
 /**
  * Custom function from EuPlatesc documentation.
  * Fore more details, please read the documentation from module.
  *
  * @param string $data
  * @param string $key
  * @return string.
  */
  private static function hashSHA1($data, $key) {
   $blocksize = 64;
   $hashfunc = 'md5';

   if (strlen($key) > $blocksize) {
    $key = pack('H*', $hashfunc($key));
   }

   $key = str_pad($key, $blocksize, chr(0x00));
   $ipad = str_repeat(chr(0x36), $blocksize);
   $opad = str_repeat(chr(0x5c), $blocksize);

   $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
   return bin2hex($hmac);
  }

}
