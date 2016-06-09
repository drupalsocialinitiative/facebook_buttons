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

    $form['like'] = $this->likeForm->buildForm($config);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->likeForm->validateForm($form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('facebook_buttons.settings');

    $this->likeForm->submitForm($config, $form_state);
  }
}
