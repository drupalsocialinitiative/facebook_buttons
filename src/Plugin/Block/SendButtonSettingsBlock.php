<?php

namespace Drupal\facebook_widgets_buttons\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines de send button settings form.
 */
class SendButtonSettingsBlock implements ButtonsSettingsBlockInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function blockForm(array &$config) {
    $form = array(
      '#type' => 'details',
      '#title' => $this->t('Send Button'),
      '#open' => FALSE,
    );
    $form['url'] = array(
      '#type' => 'textfield',
      '#default_value' => $config['url'],
      '#description' => $this->t('URL of the page to share (could be your homepage e.g.)<br> You can also specify &lt;current&gt; to establish the url for the current viewed page in your site'),
    );
    $form['size'] = array(
      '#type' => 'select',
      '#title' => $this->t('Button size'),
      '#options' => array(
        'small' => 'Small',
        'large' => 'Large',
      ),
      '#default_value' => $config['size'],
      '#description' => $this->t('The size of the button'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate(FormStateInterface $form_state) {
    // TODO: Implement blockValidate() method.
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit(array &$config, array &$values) {
    $config['url'] = $values['url'];
    $config['size'] = $values['size'];
  }

}
