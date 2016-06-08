<?php

/**
 * @file
 * Contains \Drupal\fblikebutton\Form\FblikebuttonFormSettings
 */

namespace Drupal\facebook_buttons\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FacebookButtonsSettings extends ConfigFormBase {

  /**
   * @var \Drupal\facebook_buttons\Form\LikeButtonSettingsForm
   */
  protected $likeForm;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('config.factory'),
                      $container->get('facebook_buttons.like_form'));
  }


  /**
   * FacebookButtonsSettings constructor.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *
   * @param \Drupal\facebook_buttons\Form\LikeButtonSettingsForm $like_form
   */
  public function __construct(ConfigFactoryInterface $config_factory, LikeButtonSettingsForm $like_form) {
    parent::__construct($config_factory);

    $this->likeForm = $like_form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'facebook_buttons_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return array('facebook_buttons.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('facebook_buttons.settings');

    $form['like'] = array(
      '#type' => 'details',
      '#title' => $this->t('Like Button'),
      '#open' => FALSE,
    );

    $form['like'][] = $this->likeForm->buildLikeForm($config);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (null != $form_state->getValue('weight')) {
      if (!is_numeric($form_state->getValue('weight'))) {
        $form_state->setErrorByName('weight', $this->t('The weight of the like button must be a number (examples: 50 or -42 or 0).'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('facebook_buttons.settings');

    $node_types = $form_state->getValue('node_types');
    $full_node_display = $form_state->getValue('full_node_display');
    $teaser_display = $form_state->getValue('teaser_display');
    $layout = $form_state->getValue('layout');
    $show_faces = $form_state->getValue('show_faces');
    $action = $form_state->getValue('action');
    $font = $form_state->getValue('font');
    $color_scheme = $form_state->getValue('color_scheme');
    $weight = $form_state->getValue('weight');
    $language = $form_state->getValue('language');

    $config->set('node_types', $node_types)
          ->set('full_node_display', $full_node_display)
          ->set('teaser_display', $teaser_display)
          ->set('layout', $layout)
          ->set('show_faces', $show_faces)
          ->set('action', $action)
          ->set('font', $font)
          ->set('color_scheme', $color_scheme)
          ->set('weight', $weight)
          ->set('language', $language)
          ->save();

    // Clear render cache
    $this->clearCache();
  }

  protected function buildLikeForm(ConfigFactoryInterface &$config, array &$form) {

  }

  /**
   * @TODO Clear render cache to make the button use the new configration
   */
  protected function clearCache() {
    \Drupal::cache('render')->invalidateAll();
  }
}
