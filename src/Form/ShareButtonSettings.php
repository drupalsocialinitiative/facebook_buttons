<?php
/**
 * @file
 * Contains Drupal\facebook_buttons\Form\ShareButtonSettings
 */

namespace Drupal\facebook_buttons\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ShareButtonSettings extends ConfigFormBase
{

  /**
   * @inheritdoc
   */
  protected function getEditableConfigNames()
  {
    // TODO: Implement getEditableConfigNames() method.
  }

  /**
   * @inheritdoc
   */
  public function getFormId()
  {
    return 'facebook_share_button_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['share'] = array(
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => 'Someone should code this button'
    );

    return $form;
  }
}