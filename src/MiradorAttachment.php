<?php

/**
 * @file
 * Contains Drupal\mirador\PageAttachmentInterface.
 */

namespace Drupal\mirador;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * An implementation of PageAttachmentInterface for the mirador library.
 */
class MiradorAttachment implements ElementAttachmentInterface {
  /**
   * The service to determin if mirador should be activated.
   *
   * @var \Drupal\mirador\ActivationCheckInterface
   */
  protected $activation;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The mirdor settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $settings;

  /**
   * Create an instance of MiradorAttachment.
   */
  public function __construct(ActivationCheckInterface $activation, ModuleHandlerInterface $module_handler, ConfigFactoryInterface $config) {
    $this->activation = $activation;
    $this->moduleHandler = $module_handler;
    $this->settings = $config->get('mirador.settings');;
  }

  /**
   * {@inheritdoc}
   */
  public function isApplicable() {
    return !drupal_installation_attempted() && $this->activation->isActive();
  }

  /**
   * {@inheritdoc}
   */
  public function attach(array &$page) {
    if ($this->settings->get('custom.activate')) {
      $js_settings = array(

      );
    }
    else {
      $js_settings = array(

      );
    }

    $style = $this->settings->get('custom.style');

    // Add mirador js settings.
    $page['#attached']['drupalSettings']['mirador'] = $js_settings;

    // Add and initialise the Mirador plugin.
    if ($this->settings->get('advanced.compression_type' == 'minified')) {
      $page['#attached']['library'][] = 'mirador/mirador';
    }

    // Add JS and CSS based on selected style.
    if ($style != 'none') {
      $page['#attached']['library'][] = "mirador/$style";
    }
  }

}
