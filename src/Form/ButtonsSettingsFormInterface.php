<?php

namespace Drupal\facebook_widgets_buttons\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\Form\FormStateInterface;

/**
 * Interface ButtonsSettingsFormInterface.
 *
 * @package Drupal\facebook_widgets_buttons\Form
 */
interface ButtonsSettingsFormInterface {

  /**
   * Build the form.
   *
   * @param \Drupal\Core\Config\Config $config
   *   The configuration manager.
   *
   * @return array
   *   A render array.
   */
  public function buildForm(Config $config);

  /**
   * Validates the form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function validateForm(FormStateInterface $form_state);

  /**
   * Saves values in the form.
   *
   * @param \Drupal\Core\Config\Config $config
   *   The configuration manager.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function submitForm(Config $config, FormStateInterface $form_state);

}
