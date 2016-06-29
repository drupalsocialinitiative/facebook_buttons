<?php

namespace Drupal\facebook_buttons\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class FacebookButtonsBlock
 *
 * @package Drupal\facebook_buttons\Plugin\Block
 *
 *
 * Provides a Facebook Like Button Block
 *
 * @Block(
 *   id = "facebook_buttons_block",
 *   admin_label = @Translation("Facebook Buttons"),
 * )
 */
class FacebookButtonsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\facebook_buttons\Plugin\Block\LikeButtonSettingsBlock
   */
  private $likeButton;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('facebook_buttons.like_settings_block'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * FacebookButtonsBlock constructor.
   *
   * @param \Drupal\facebook_buttons\Plugin\Block\LikeButtonSettingsBlock $like_button
   * @param array $configuration
   * @param mixed $plugin_id
   * @param $plugin_definition
   */
  public function __construct(LikeButtonSettingsBlock $like_button, array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->likeButton = $like_button;
  }

  /**
  * {@inheritdoc}
  */
  public function build() {

    $block = array(
      '#theme' => 'facebook-buttons',
      '#layout' => $this->configuration['like']['layout'],
      '#show_faces' => $this->configuration['like']['show_faces'],
      '#action' => $this->configuration['like']['action'],
      '#size' => $this->configuration['like']['size'],
      '#width' => $this->configuration['like']['width'],
      '#language' => $this->configuration['like']['language'],
      '#buttons' => array('like' => TRUE)
    );

    // If it's not for the current page
    if($this->configuration['like']['block_url'] != '<current>') {
      $block['#url'] = $this->configuration['like']['block_url'];
    } else {
      // Avoid this block to be cached
      $block['#cache'] = array(
        'max-age' => 0,
      );
      
      /**
       * Drupal uses the /node path to refers to the frontpage. That's why facebook
       * could point to www.example.com/node instead of wwww.example.com.
       * 
       * To avoid this, we check if the current path is the frontpage
       */
      if(\Drupal::routeMatch()->getRouteName() == 'view.frontpage.page_1') {
        global $base_url;
        $block['#url'] = $base_url;
      } else {
        $block['#url'] = Url::fromRoute('<current>', array(), array('absolute' => true))->toString();
      }
    }

    return $block;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    global $base_url;
    return array(
      'like' => array(
        'block_url' => $base_url,
        'layout' => 'standard',
        'show_faces' => TRUE,
        'action' => 'like',
        'font' => 'arial',
        'color_scheme' => 'light',
        'language' => 'en_US',
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state ) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['like'] = $this->likeButton->blockForm($config);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->likeButton->blockSubmit($this->configuration, $form_state);
  }
}
