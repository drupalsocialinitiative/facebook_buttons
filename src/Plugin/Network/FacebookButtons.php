<?php

namespace Drupal\facebook_widgets_buttons\Plugin\Network;

use Drupal\social_api\Plugin\NetworkBase;

/**
 * Creates a network plugin for Facebook Widgets Buttons.
 *
 * @Network(
 *   id = "facebook_buttons",
 *   social_network = "Facebook",
 *   type = "social_widgets"
 * )
 */
class FacebookButtons extends NetworkBase implements FacebookButtonsInterface {

  /**
   * Facebook widgets require the Javascript SDK, leaving this method empty.
   */
  protected function initSdk() {

  }

}
