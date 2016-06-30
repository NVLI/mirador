<?php

/**
 * @file
 * Mirador theme functions.
 */

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Unicode;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\Yaml\Parser;
use \Drupal\user\Entity\User;

/**
 * Prepares variables for mirador formatter templates.
 *
 * Default template: mirador-formatter.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - item: An ImageItem object.
 *   - item_attributes: An optional associative array of html attributes to be
 *     placed in the img tag.
 *   - entity: An entity object.
 *   - settings: Formatter settings array.
 */
function template_preprocess_mirador_formatter(&$variables) {
  global $base_url;
  $uid = \Drupal::currentUser()->id();
  $item = $variables['item'];
  $item_attributes = isset($variables['item_attributes']) ? $variables['item_attributes'] : array();
  $entity = $variables['entity'];
  $settings = $variables['settings'];

  // Reset the $variables parameter.
  $variables = array();

  $classes_array = array('mirador');

  // Get the entity type.
  $entity_type_id = $entity->getEntityTypeId();

  // Build the gallery id.
  $id = $entity->id();
  $field_name = $item->getParent()->getName();
  $mirador_viewer_id = 'mirador-viewer-' . $id;

  // Parse the mirador settings YAML.
  $yaml = new Parser();
  $mirador_settings_array = $yaml->parse($settings['mirador_settings']);
  $mirador_settings_array['resolver_type'] = $settings['resolver_type'];
  $mirador_settings = serialize($mirador_settings_array);

  // Generate manifest uri and pass to jquery.
  $manifest_uri = $base_url . '/mirador/manifest/' . $entity_type_id . '/' . $field_name . '/' . $id . '/' . $mirador_settings;
  // Generate annotation uri.
  $annotation_uri = $base_url . '/mirador/annotation';
  $variables['#attached']['drupalSettings']['init']['entity']['manifest_uri'] = $manifest_uri;
  $variables['#attached']['drupalSettings']['init']['entity']['viewer_id'] = $mirador_viewer_id;
  $variables['#attached']['drupalSettings']['init']['entity']['entity_id'] = $id;
  $variables['#attached']['drupalSettings']['init']['entity']['user_id'] = $uid;
  $variables['#attached']['drupalSettings']['init']['entity']['annotation_uri'] = $annotation_uri;

  $variables['attributes'] = new Attribute();
  $variables['attributes']['class'] = $classes_array;
  $variables['attributes']['id'] = $mirador_viewer_id;
}
