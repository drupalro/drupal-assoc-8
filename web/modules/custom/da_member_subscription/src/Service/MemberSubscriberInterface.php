<?php

namespace Drupal\da_member_subscription\Service;

interface MemberSubscriberInterface {

  public function createSubscription($subscriptionType);

  public function renewSubscription();

}
