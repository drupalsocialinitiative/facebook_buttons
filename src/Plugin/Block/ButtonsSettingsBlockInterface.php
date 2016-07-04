<?php
/**
 * @file
 * Contains \Drupal\facebook_widgets_buttons\Plugin\Block\ButtonsSettingsFormInterface
 */

namespace Drupal\facebook_widgets_buttons\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;

/**
 * Interface ButtonsSettingsFormInterface
 *
 * @package Drupal\facebook_widgets_buttons\Form
 */
interface ButtonsSettingsBlockInterface {

  /**
   * Build the form
   *
   * @param array $config
   *
   * @return array
   */
  public function blockForm(array &$config);

  /**
   * Validates the form
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function blockValidate(FormStateInterface $form_state);


  /**
   * Saves values in the form
   *
   * @param array $config
   * @param array $values
   */
  public function blockSubmit(array &$config, array &$values);

}
