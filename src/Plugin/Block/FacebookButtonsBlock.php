<?php

namespace Drupal\facebook_buttons\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Routing\RouteMatchInterface;
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
   * @var \Drupal\facebook_buttons\Plugin\Block\ShareButtonSettingsBlock
   */
  private $shareButton;

  /**
   * @var \Drupal\facebook_buttons\Plugin\Block\SendButtonSettingsBlock
   */
  private $sendButton;

  /**
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  private $routeMatch;

  /**
   * Block render array
   *
   * @var array
   */
  private $block;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('facebook_buttons.like_settings_block'),
      $container->get('facebook_buttons.share_settings_block'),
      $container->get('facebook_buttons.send_settings_block'),
      $container->get('current_route_match'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * FacebookButtonsBlock constructor.
   *
   * @param \Drupal\facebook_buttons\Plugin\Block\LikeButtonSettingsBlock $like_button
   * @param \Drupal\facebook_buttons\Plugin\Block\ShareButtonSettingsBlock $share_button
   * @param \Drupal\facebook_buttons\Plugin\Block\SendButtonSettingsBlock $send_button
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route_match
   * @param array $configuration
   * @param mixed $plugin_id
   * @param $plugin_definition
   */
  public function __construct(LikeButtonSettingsBlock $like_button, ShareButtonSettingsBlock $share_button,
                              SendButtonSettingsBlock $send_button, CurrentRouteMatch $route_match,
                              array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->likeButton = $like_button;
    $this->shareButton = $share_button;
    $this->sendButton = $send_button;
    $this->routeMatch = $route_match;
  }

  /**
  * {@inheritdoc}
  */
  public function build() {

    $this->block = array(
      // General setttings
      '#theme' => 'facebook-buttons',
      '#buttons' => $this->configuration['buttons'],
      // Like settings
      '#like_layout' => $this->configuration['like']['layout'],
      '#like_show_faces' => $this->configuration['like']['show_faces'],
      '#like_action' => $this->configuration['like']['action'],
      '#like_size' => $this->configuration['like']['size'],
      '#like_width' => $this->configuration['like']['width'],
      // Share settings
      '#share_layout' => $this->configuration['share']['layout'],
      '#share_size' => $this->configuration['share']['size'],
      // Send settings
      '#send_size' => $this->configuration['send']['size'],
    );

    foreach($this->block['#buttons'] as $button => $title) {
      $this->checkUrl($button, $this->configuration[$button]['url']);
    }

    return $this->block;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    global $base_url;
    return array(
      'like' => array(
        'url' => $base_url,
        'layout' => 'standard',
        'show_faces' => TRUE,
        'action' => 'like',
        'size' => 'small',
        'share' => TRUE,
        'width' => '',
      ),
      'share' => array(
        'url' => $base_url,
        'layout' => 'button_count',
        'size' => 'small',
      ),
      'send' => array(
        'url' => $base_url,
        'size' => 'small',
      ),
      'buttons' => array('like' => 'like'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state ) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['like'] = $this->likeButton->blockForm($config['like']);
    $form['share'] = $this->shareButton->blockForm($config['share']);
    $form['send'] = $this->sendButton->blockForm($config['send']);

    $form['buttons'] = array(
      '#type' => 'checkboxes',
      '#title' => 'Which buttons should be displayed?',
      '#options' => array('like' => 'Like', 'share' => 'Share', 'send' => 'Send'),
      '#default_value' => $config['buttons'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $this->likeButton->blockSubmit($this->configuration['like'], $values['like']);
    $this->shareButton->blockSubmit($this->configuration['share'], $values['share']);
    $this->sendButton->blockSubmit($this->configuration['send'], $values['send']);
    $this->configuration['buttons'] = $values['buttons'];
  }

  /**
   * Check if buttons url point to <current>
   *
   * @param $button
   *    The button of which to check the url
   * @param $url
   *    The url provided in the button form
   */
  protected function checkUrl($button, $url) {
    // If it's not for the current page
    if($url != '<current>') {
      $this->block['#'.$button.'_url'] = $url;
    } else {
      /**
       * Drupal uses the /node path to refers to the frontpage. That's why facebook
       * could point to www.example.com/node instead of wwww.example.com.
       *
       * To avoid this, we check if the current path is the frontpage
       */
      if($this->routeMatch->getRouteName() == 'view.frontpage.page_1') {
        global $base_url;
        $this->block['#'.$button.'_url'] = $base_url;
      } else {
        $this->block['#'.$button.'_url'] = Url::fromRoute('<current>', array(), array('absolute' => TRUE))
          ->toString();
      }

      if(!isset($this->block['#cache'])) {
        // Avoid this block to be cached
        $this->block['#cache'] = array(
          'max-age' => 0,
        );
      }
    }
  }
}
