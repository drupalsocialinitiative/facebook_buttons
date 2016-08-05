<?php

namespace Drupal\facebook_widgets_buttons\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines de like button settings form.
 */
class LikeButtonSettingsBlock implements ButtonsSettingsBlockInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function blockForm(array &$config) {
    $form = array(
      '#type' => 'details',
      '#title' => $this->t('Like Button'),
      '#open' => FALSE,
    );
    $form['settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Button settings'),
      '#open' => TRUE,
    );
    $form['settings']['url'] = array(
      '#type' => 'textfield',
      '#default_value' => $config['url'],
      '#description' => $this->t('URL of the page to like (could be your homepage or a facebook page e.g.)<br> You can also specify &lt;current&gt; to establish the url for the current viewed page in your site'),
    );

    $form['appearance'] = array(
      '#type' => 'details',
      '#title' => $this->t('Button appearance'),
      '#open' => FALSE,
    );
    $form['appearance']['layout'] = array(
      '#type' => 'select',
      '#title' => $this->t('Layout style'),
      '#options' => array(
        'standard' => $this->t('Standard'),
        'box_count' => $this->t('Box Count'),
        'button_count' => $this->t('Button Count'),
        'button' => $this->t('Button'),
      ),
      '#default_value' => $config['layout'],
      '#description' => $this->t('Determines the size and amount of social context next to the button'),
    );
    $form['appearance']['show_faces'] = array(
      '#type' => 'select',
      '#title' => $this->t('Display faces in the box'),
      '#options' => array($this->t('Do not show faces'), $this->t('Show faces')),
      '#default_value' => $config['show_faces'],
      '#description' => $this->t('Show profile pictures below the button. Only works with Standard layout'),
    );
    $form['appearance']['action'] = array(
      '#type' => 'select',
      '#title' => $this->t('Verb to display'),
      '#options' => array('like' => $this->t('Like'), 'recommend' => $this->t('Recommend')),
      '#default_value' => $config['action'],
      '#description' => $this->t('The verb to display in the button.'),
    );
    $form['appearance']['size'] = array(
      '#type' => 'select',
      '#title' => $this->t('Button size'),
      '#options' => array(
        'small' => 'Small',
        'large' => 'Large',
      ),
      '#default_value' => $config['size'],
      '#description' => $this->t('The size of the button'),
    );
    $form['appearance']['share'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Do you want to show the share button?'),
      '#options' => array($this->t('No'), $this->t('Yes')),
      '#default_value' => (int) $config['share'],
      '#description' => $this->t('If you want to show the share button, select yes.
                        This button is different from the below share button'),
    );
    $form['appearance']['width'] = array(
      '#type' => 'number',
      '#title' => $this->t('Button width'),
      '#default_value' => $config['width'],
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
    $url = $values['settings']['url'];
    $layout = $values['appearance']['layout'];
    $show_faces = (bool) $values['appearance']['show_faces'];
    $action = $values['appearance']['action'];
    $size = $values['appearance']['size'];
    $share = (bool) $values['appearance']['share'];
    $width = $values['appearance']['width'];
    $config['url'] = $url;
    $config['layout'] = $layout;
    $config['show_faces'] = $show_faces;
    $config['block_url'] = $url;
    $config['action'] = $action;
    $config['size'] = $size;
    $config['share'] = $share;
    $config['width'] = $width;
  }

}
