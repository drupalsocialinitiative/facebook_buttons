<?php
/**
 * @file
 * Contains Drupal\facebook_buttons\Form\LikeButtonSettings Form
 */

namespace Drupal\facebook_buttons\Form;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;


/**
 * Class LikeButtonSettingsForm
 *
 * @package Drupal\facebook_buttons\Form
 */
class LikeButtonSettingsForm implements ButtonsSettingsFormInterface {

  use StringTranslationTrait;

  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface $renderCache
   */
  private $renderCache;


  /**
   * LikeButtonSettingsForm constructor.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   */
  public function __construct(CacheBackendInterface $cache) {
    $this->renderCache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(Config $config) {

    $node_options = node_type_get_names();

    $form = array(
      '#type' => 'details',
      '#title' => $this->t('Like Button'),
      '#open' => TRUE,
    );

    $form['visibility'] = array(
      '#type' => 'details',
      '#title' => $this->t('Visibility settings'),
      '#open' => TRUE,
    );
    $form['visibility']['node_types'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Display the Like button on these content types:'),
      '#options' => $node_options,
      '#default_value' => $config->get('like.node_types') ? $config->get('like.node_types') : array(),
      '#description' => $this->t('Each of these content types will have the "like" button automatically added to them.'),
    );
    $form['visibility']['teaser_display'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Do you want to show the Like button on teasers?'),
      '#options' => array($this->t('No'),$this->t('Yes')),
      '#default_value' => (int) $config->get('like.teaser_display'),
      '#description' => $this->t('If you want to show the like button on teasers, select yes.'),
    );

    $form['appearance'] = array(
      '#type' => 'details',
      '#title' => $this->t('Appearance settings'),
      '#open' => FALSE,
    );
    $form['appearance']['layout'] = array(
      '#type' => 'select',
      '#title' => $this->t('Layout style'),
      '#options' => array(
        'standard' => $this->t('Standard'),
        'box_count' => $this->t('Box Count'),
        'button_count' => $this->t('Button Count'),
        'button' => $this->t('Button')),
      '#default_value' => $config->get('like.layout'),
      '#description' => $this->t('Determines the size and amount of social context next to the button.'),
    );
    $form['appearance']['show_faces'] = array(
      '#type' => 'select',
      '#title' => $this->t('Show faces in the box?'),
      '#options' => array($this->t('Do not show faces'), $this->t('Show faces')),
      '#default_value' => $config->get('like.show_faces'),
      '#description' => $this->t('Show profile pictures below the button. Only works if <em>Layout style</em> (found above)
                                 is set to <em>Standard</em> (otherwise, value is ignored).'),
    );
    $form['appearance']['action'] = array(
      '#type' => 'select',
      '#title' => $this->t('Verb to display'),
      '#options' => array('like' => $this->t('Like'), 'recommend' => $this->t('Recommend')),
      '#default_value' => $config->get('like.action'),
      '#description' => $this->t('The verbiage to display inside the button itself.'),
    );
    $form['appearance']['size'] = array(
      '#type' => 'select',
      '#title' => $this->t('Size'),
      '#options' => array(
        'small' => 'Small',
        'large' => 'Large'
      ),
      '#default_value' => $config->get('like.size'),
      '#description' => $this->t('The size of the button.'),
    );
    $form['appearance']['width'] = array(
      '#type' => 'number',
      '#title' => $this->t('Button width'),
      '#default_value' => $config->get('like.width')
    );
    $form['appearance']['share'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Do you want to show the share button?'),
      '#options' => array($this->t('No'),$this->t('Yes')),
      '#default_value' => (int) $config->get('like.share'),
      '#description' => $this->t('If you want to show the share button, select yes.
                        This button is different from the below share button'),
    );
    $form['appearance']['weight'] = array(
      '#type' => 'number',
      '#title' => $this->t('Weight'),
      '#default_value' => $config->get('like.weight'),
      '#description' => $this->t('The weight determines where, at the content block, the like button will appear.
                        The larger the weight, the lower it will appear on the node. For example, if you want the
                        button to appear more toward the top of the node, choose <em>-40</em> as opposed to
                        <em>-39, -38, 0, 1,</em> or <em>50,</em> etc. To position the Like button in its own block,
                        go to the ' . \Drupal::l($this->t('block page'), Url::fromRoute('block.admin_display')) . '.'),
    );

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(FormStateInterface $form_state) {
    $weight = $form_state->getValue(['like', 'weight']);
    if (null != $weight) {
      if (!is_numeric($weight)) {
        $form_state->setErrorByName(array('like', 'weight'), $this->t('The weight of the like button must be a number (examples: 50 or -42 or 0).'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(Config $config, FormStateInterface $form_state) {

    $node_types = $form_state->getValue('node_types');
    $teaser_display = (bool) $form_state->getValue('teaser_display');
    $layout = $form_state->getValue('layout');
    $show_faces = (bool) $form_state->getValue('show_faces');
    $action = $form_state->getValue('action');
    $size = $form_state->getValue('size');
    $width = $form_state->getValue('width');
    $weight = $form_state->getValue('weight');
    $share = $form_state->getValue('share');

    $config->set('like.node_types', $node_types)
      ->set('like.teaser_display', $teaser_display)
      ->set('like.layout', $layout)
      ->set('like.show_faces', $show_faces)
      ->set('like.action', $action)
      ->set('like.size', $size)
      ->set('like.width', $width)
      ->set('like.share', $share)
      ->set('like.weight', $weight)
      ->save();

    // Clear render cache
    $this->clearCache();
  }

  /**
   * @TODO Clear render cache to make the button use the new configuration
   */
  protected function clearCache() {
    $this->renderCache->invalidateAll();
  }
}
