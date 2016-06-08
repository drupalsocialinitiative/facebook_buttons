<?php
/**
 * @file
 * Contains Drupal\facebook_buttons\Form\LikeButtonSettings Form
 */

namespace Drupal\facebook_buttons\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;


/**
 * Class LikeButtonSettingsForm
 *
 * @package Drupal\facebook_buttons\Form
 */
class LikeButtonSettingsForm {

  use StringTranslationTrait;

  public function buildLikeForm(Config &$config) {

    $node_options = node_type_get_names();

    $form['visibility'] = array(
      '#type' => 'details',
      '#title' => $this->t('Visibility settings'),
      '#open' => TRUE,
    );
    $form['visibility']['node_types'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Display the Like button on these content types:'),
      '#options' => $node_options,
      '#default_value' => $config->get('node_types') ? $config->get('node_types') : array(),
      '#description' => $this->t('Each of these content types will have the "like" button automatically added to them.'),
    );
    $form['visibility']['teaser_display'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Where do you want to show the Like button on teasers?'),
      '#options' => array(
        $this->t('Don\'t show on teasers'),
        $this->t('Content area'),
      ),
      '#default_value' => $config->get('teaser_display'),
      '#description' => $this->t('If you want to show the like button on teasers you can select the display area.'),
    );

    $form['appearance'] = array(
      '#type' => 'details',
      '#title' => $this->t('Appearance settings'),
      '#open' => TRUE,
    );
    $form['appearance']['layout'] = array(
      '#type' => 'select',
      '#title' => $this->t('Layout style'),
      '#options' => array('standard' => $this->t('Standard'),
        'box_count' => $this->t('Box Count'),
        'button_count' => $this->t('Button Count'),
        'button' => $this->t('Button')),
      '#default_value' => $config->get('layout'),
      '#description' => $this->t('Determines the size and amount of social context next to the button.'),
    );
    // The actial values passed in from the options will be converted to a boolean
    // in the validation function, so it doesn't really matter what we use.
    $form['appearance']['show_faces'] = array(
      '#type' => 'select',
      '#title' => $this->t('Show faces in the box?'),
      '#options' => array(t('Do not show faces'), $this->t('Show faces')),
      '#default_value' => $config->get('show_faces', TRUE),
      '#description' => $this->t('Show profile pictures below the button. Only works if <em>Layout style</em> (found above) is set to <em>Standard</em> (otherwise, value is ignored).'),
    );
    $form['appearance']['action'] = array(
      '#type' => 'select',
      '#title' => $this->t('Verb to display'),
      '#options' => array('like' => $this->t('Like'), 'recommend' => $this->t('Recommend')),
      '#default_value' => $config->get('action'),
      '#description' => $this->t('The verbiage to display inside the button itself.'),
    );
    $form['appearance']['font'] = array(
      '#type' => 'select',
      '#title' => $this->t('Font'),
      '#options' => array(
        'arial' => 'Arial',
        'lucida+grande' => 'Lucida Grande',
        'segoe+ui' => 'Segoe UI',
        'tahoma' => 'Tahoma',
        'trebuchet+ms' => 'Trebuchet MS',
        'verdana' => 'Verdana',
      ),
      '#default_value' => $config->get('font', 'arial'),
      '#description' => $this->t('The font with which to display the text of the button.'),
    );
    $form['appearance']['color_scheme'] = array(
      '#type' => 'select',
      '#title' => $this->t('Color scheme'),
      '#options' => array('light' => $this->t('Light'), 'dark' => $this->t('Dark')),
      '#default_value' => $config->get('color_scheme'),
      '#description' => $this->t('The color scheme of the box environtment.'),
    );
    $form['appearance']['weight'] = array(
      '#type' => 'number',
      '#title' => $this->t('Weight'),
      '#default_value' => $config->get('weight'),
      '#description' => $this->t('The weight determines where, at the content block, the like button will appear. The larger the weight, the lower it will appear on the node. For example, if you want the button to appear more toward the top of the node, choose <em>-40</em> as opposed to <em>-39, -38, 0, 1,</em> or <em>50,</em> etc. To position the Like button in its own block, go to the ' . \Drupal::l($this->t('block page'), Url::fromRoute('block.admin_display')) . '.'),
    );
    $form['appearance']['language'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Language'),
      '#default_value' => $config->get('language'),
      '#description' => $this->t('Specific language to use. Default is English. Examples:<br />French (France): <em>fr_FR</em><br />French (Canada): <em>fr_CA</em><br />More information can be found at http://developers.facebook.com/docs/internationalization/ and a full XML list can be found at http://www.facebook.com/translations/FacebookLocales.xml'),
    );

    return $form;
  }
}
