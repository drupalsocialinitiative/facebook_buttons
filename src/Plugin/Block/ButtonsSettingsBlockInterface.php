<?php

namespace Drupal\facebook_widgets_buttons\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;

/**
 * Defines an interface for Facebook Buttons Block Settings Form.
 */
interface ButtonsSettingsBlockInterface {

  /**
   * Build the form.
   *
   * @param array $config
   *   The block configuration array.
   *
   * @return array
   *   The render array.
   */
  public function blockForm(array &$config);

  /**
   * Validates the form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function blockValidate(FormStateInterface $form_state);

  /**
   * Saves values in the form.
   *
   * @param array $config
   *   The block configuration array.
   * @param array $values
   *   The values to store.
   */
  public function blockSubmit(array &$config, array &$values);

}
