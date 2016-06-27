<?php

/**
 * @file
 * Mirador theme functions.
 */

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Unicode;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\Yaml\Parser;

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
  $caption = NULL;
  global $base_url;

  $item = $variables['item'];
  $item_attributes = isset($variables['item_attributes']) ? $variables['item_attributes'] : array();
  $entity = $variables['entity'];
  $settings = $variables['settings'];

  // Reset the $variables parameter.
  $variables = array();
  $image_uri = $item->entity->getFileUri();
  $classes_array = array('mirador');

  // Get the entity type.
  $entity_type_id = $entity->getEntityTypeId();

  // Build the gallery id.
  $id = $entity->id();
  $field_name = $item->getParent()->getName();
  $mirador_viewer_id = 'image-viewer-' . $id;

  // Parse the mirador settings YAML.
  $yaml = new Parser();
  $mirador_settings_array = $yaml->parse($settings['mirador_settings']);
  $mirador_settings = serialize($mirador_settings_array);

  // Generate manifest uri and pass to jquery.
  $manifest_uri = $base_url . '/mirador/manifest/' . $entity_type_id . '/' . $field_name . '/' . $id . '/' . $mirador_settings;
  $variables['#attached']['drupalSettings']['init']['entity']['manifest_uri'] = $manifest_uri;
  $variables['#attached']['drupalSettings']['init']['entity']['viewer_id'] = $mirador_viewer_id;

  // Set up the $variables['image'] parameter.
  if ($settings['style_name'] == 'hide') {
    $variables['image'] = array();
    $classes_array[] = 'js-hide';
  }
  elseif (!empty($settings['style_name'])) {
    $variables['image'] = array(
      '#theme' => 'image_style',
      '#style_name' => $settings['style_name'],
    );
  }
  else {
    $variables['image'] = array(
      '#theme' => 'image',
    );
  }

  if (!empty($variables['image'])) {
    $variables['image']['#attributes'] = $item_attributes;

    // Do not output an empty 'title' attribute.
    if (Unicode::strlen($item->title) != 0) {
      $variables['image']['#title'] = $item->title;
    }

    foreach (array('width', 'height', 'alt') as $key) {
      $variables['image']["#$key"] = $item->$key;
    }

    $variables['image']['#uri'] = empty($item->uri) ? $image_uri : $item->uri;
  }

  if (!empty($settings['mirador_image_style'])) {
    $style = ImageStyle::load($settings['mirador_image_style']);
    $variables['url'] = $style->buildUrl($image_uri);
  }
  else {
    $variables['url'] = file_create_url($image_uri);
  }

  // If File Entity module is enabled, load attribute values from file entity.
  if (\Drupal::moduleHandler()->moduleExists('file_entity')) {
    // File id of the save file.
    $fid = $item->target_id;
    // Load file object.
    $file_obj = file_load($fid);
    $file_array = $file_obj->toArray();
    // Populate the image title.
    if (Unicode::strlen($file_array['field_image_title_text'][0]['value']) != 0 && empty($item->title)) {
      $caption = $file_array['field_image_title_text'][0]['value'];
    }
    // Populate the image alt text.
    if (!empty($file_array['field_image_alt_text'][0]['value']) && empty($item->alt)) {
      $caption = $file_array['field_image_alt_text'][0]['value'];
    }
  }
  $variables['attributes'] = new Attribute();
  $variables['attributes']['title'] = $caption;
  $variables['attributes']['class'] = $classes_array;
  $variables['attributes']['id'] = $mirador_viewer_id;
}
