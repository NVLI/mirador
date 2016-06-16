<?php

/**
 * @file
 * Mirador theme functions.
 */

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Unicode;
use Drupal\image\Entity\ImageStyle;

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
  static $gallery_token = NULL;

  $item = $variables['item'];
  $item_attributes = isset($variables['item_attributes']) ? $variables['item_attributes'] : array();
  $entity = $variables['entity'];
  $settings = $variables['settings'];
  $variables = array(); // Reset the $variables parameter.
  $image_uri = $item->entity->getFileUri();
  $classes_array = array('mirador');

  // Build the caption.
  $entity_title = $entity->label();
  $entity_type = $entity->bundle();


  // Build the gallery id.
  $id = $entity->id();
  $entity_id = !empty($id) ? $entity_type . '-' . $id : 'entity-id';
  $field_name = $item->getParent()->getName();



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

  // if File Entity module is enabled, load attribute values from file entity.
  if(\Drupal::moduleHandler()->moduleExists('file_entity')) {
    // file id of the save file.
    $fid = $item->target_id;
    // load file object
    $file_obj = file_load($fid);
    $file_array = $file_obj->toArray();
    // populate the image title
    if (Unicode::strlen($file_array['field_image_title_text'][0]['value']) != 0 && empty($item->title)) {
      $caption = $file_array['field_image_title_text'][0]['value'];
    }
    // populate the image alt text.
    if (!empty($file_array['field_image_alt_text'][0]['value']) && empty($item->alt)) {
      $caption = $file_array['field_image_alt_text'][0]['value'];
    }
  }

  $variables['attributes'] = new Attribute();
  $variables['attributes']['title'] = $caption;
  $variables['attributes']['class'] = $classes_array;
}
