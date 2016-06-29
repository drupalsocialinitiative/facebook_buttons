<?php
/**
 * @file
 * Contains \Drupal\facebook_buttons\Plugin\Block\LikeButtonSettingsBlock
 */

namespace Drupal\facebook_buttons\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class LikeButtonSettingsBlock
 *
 * @package Drupal\facebook_buttons\Plugin\Block
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
    $form['settings']['block_url'] = array(
      '#type' => 'textfield',
      '#default_value' => $config['like']['block_url'],
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
      '#options' => array('standard' => $this->t('Standard'),
        'box_count' => $this->t('Box Count'),
        'button_count' => $this->t('Button Count'),
        'button' => $this->t('Button')),
      '#default_value' => $config['like']['layout'],
      '#description' => $this->t('Determines the size and amount of social context next to the button'),
    );
    $form['appearance']['show_faces'] = array(
      '#type' => 'select',
      '#title' => $this->t('Display faces in the box'),
      '#options' => array(TRUE => $this->t('Show faces'), FALSE => $this->t('Do not show faces')),
      '#default_value' => $config['like']['show_faces'],
      '#description' => $this->t('Show profile pictures below the button. Only works with Standard layout'),
    );
    $form['appearance']['action'] = array(
      '#type' => 'select',
      '#title' => $this->t('Verb to display'),
      '#options' => array('like' => $this->t('Like'), 'recommend' => $this->t('Recommend')),
      '#default_value' => $config['like']['action'],
      '#description' => $this->t('The verb to display in the button.'),
    );
    $form['appearance']['size'] = array(
      '#type' => 'select',
      '#title' => $this->t('Button size'),
      '#options' => array(
        'small' => 'Small',
        'large' => 'Large',
      ),
      '#default_value' => $config['like']['size'],
      '#description' => $this->t('The size of the button'),
    );
    $form['appearance']['width'] = array(
      '#type' => 'number',
      '#title' => $this->t('Button width'),
      '#default_value' => $config['like']['width'],
    );
    $form['appearance']['language'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Language'),
      '#default_value' => $config['like']['language'],
      '#description' => $this->t("Specific language to use. Default is English. Examples:<br />French (France): <em>fr_FR</em><br />French (Canada): <em>fr_CA</em><br />
                        More information can be found at <a href=\"@info_url\">@info_url</a> and a full XML list can be found at<a href=\"@list_url\">@list_url</a>",
                        array(
                        "@info_url" => "http://developers.facebook.com/docs/internationalization",
                        "@list_url" => "http://www.facebook.com/translations/FacebookLocales.xml")));

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
  public function blockSubmit(array &$config, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $block_url = $values['like']['settings']['block_url'];
    $layout = $values['like']['appearance']['layout'];
    $show_faces = $values['like']['appearance']['show_faces'];
    $action = $values['like']['appearance']['action'];
    $size = $values['like']['appearance']['size'];
    $width = $values['like']['appearance']['width'];
    $language = $values['like']['appearance']['language'];

    $config['like']['block_url'] = $block_url;
    $config['like']['layout'] = $layout;
    $config['like']['show_faces'] = $show_faces;
    $config['like']['block_url'] = $block_url;
    $config['like']['action'] = $action;
    $config['like']['size'] = $size;
    $config['like']['width'] = $width;
    $config['like']['language'] = $language;
  }
}
