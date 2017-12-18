<?php

namespace Drupal\da_member_subscription\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\da_member_subscription\Entity\SubscriptionType;
use Drupal\da_member_subscription\Service\MemberSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChooseSubscriptionForm.
 *
 * @package Drupal\da_member_subscription\Form
 */
class ChooseSubscriptionForm extends FormBase {

  /**
   * @var MemberSubscriberInterface
   */
  protected $memberSubscriberService;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * ChooseSubscriptionForm constructor.
   *
   * @param MemberSubscriberInterface $memberSubscriberService
   */
  public function __construct(
    MemberSubscriberInterface $memberSubscriberService,
    EntityTypeManagerInterface $entityTypeManager
  ) {
    $this->memberSubscriberService = $memberSubscriberService;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('da_member_subscription.member_subscriber'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'da_member_subscription_choose_subscription';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['subscription_types'] = [
      '#type' => 'select',
      '#title' => $this->t('Available Subscription types'),
      '#options' => $this->getSubscriptionTypeOptions(),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Subscribe'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Generates an array of options.
   *
   * @return array
   *   An array of options.
   */
  private function getSubscriptionTypeOptions() {
    $types = $this->entityTypeManager->getStorage('da_subscription_type')->loadMultiple();
    $output = [];

    foreach ($types as $type) {
      /** @var $type SubscriptionType */
      $output[$type->id()] = $type->getName();
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $subscriptionType = $form_state->getValue('subscription_types');
    $this->memberSubscriberService->createSubscription($subscriptionType);
  }

}
