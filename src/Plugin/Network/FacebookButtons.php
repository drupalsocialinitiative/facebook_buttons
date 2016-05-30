<?php
/**
 * @file
 * Contains \Drupal\facebook_buttons\Plugin\Network
 */

namespace Drupal\facebook_buttons\Plugin\Network;

use Drupal\social_api\Plugin\NetworkBase;

/**
 * Class FacebookButtons.
 *
 * @package Drupal\facebook_buttons\Plugin\Network
 *
 * @Network(
 *   id = "facebook_buttons",
 *   social_network = "Facebook",
 *   type = "social_widgets"
 * )
 */
class FacebookButtons extends NetworkBase implements FacebookButtonsInterface {

  // Facebook widgets require the Javascript SDK, leaving this method empty
  protected function initSdk() {

  }
}
