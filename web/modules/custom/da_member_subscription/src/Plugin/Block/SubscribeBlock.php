<?php

namespace Drupal\da_member_subscription\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Link;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Template\AttributeArray;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Social Header' block.
 *
 * @Block(
 *   id = "subscribe_block",
 *   admin_label = @Translation("Subscribe Block")
 * )
 */
class SubscribeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'link',
      '#url' => Url::fromRoute('da_member_subscription.choose_subscription'),
      '#title' => 'Choose Subscription',
    ];
  }

}
