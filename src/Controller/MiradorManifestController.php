<?php

/**
 * @file
 * Contains \Drupal\mirdador\Controller\MiradorManifestController.
 */

namespace Drupal\mirador\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\mirador\SharedCanvasManifest;
use Drupal\mirador\Canvas;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller routines for manifest creation.
 */
class MiradorManifestController extends ControllerBase {

  /**
   * Page callback: Returns manifest json.
   */
  public function getManifest($entity_type, $field_name, $entity_id, $settings) {
    // Set a default value for width and height, if none specified by the user.
    $width = $height = 4217;
    $attributes = $license = $logo = NULL;
    $metadata = array();

    // Fetch the IIIF image server location from settings.
    $config = \Drupal::config('mirador.settings');
    $iiif_image_server_location = $config->get('iiif_server');
    // @to-do: Display a message if no server specified.
    // Unserialize the settings to get the settings array.
    $settings = unserialize($settings);

    // Load the entity.
    $entity = entity_load($entity_type, $entity_id);

    // Set a default label and description, if none specified by the user.
    $label = $description = $entity->get('title')->getValue();

    // Get the image field value.
    $image = $entity->get($field_name)->getValue();
    // Load the image and take file uri.
    $fid = $image[0]['target_id'];
    $file = file_load($fid);

    // Get the file mimetype.
    $mime_type = $file->get('filemime')->getValue();
    $mime_type = $mime_type[0]['value'];

    $uri = $file->getFileUri();
    // Exploding the image URI, as the public location
    // will be specified in IIF Server.
    $image_path = explode("public://", $uri);

    // Fetch the label, if specified.
    if (!empty($settings['label'])) {
      $label = $entity->get($settings['label'])->getValue();
      $label = $label[0]['value'];
      unset($settings['label']);
    }
    // Fetch the description, if specified.
    if (!empty($settings['description'])) {
      $description = $entity->get($settings['description'])->getValue();
      $description = $description[0]['value'];
      unset($settings['description']);
    }
    // Fetch the width, if specified.
    if (!empty($settings['width'])) {
      $width = $settings['width'];
      unset($settings['width']);
    }
    // Fetch the height, if specified.
    if (!empty($settings['height'])) {
      $height = $settings['height'];
      unset($settings['height']);
    }
    // Fetch the rights value, if specified.
    if (!empty($settings['license'])) {
      $license = $entity->get($settings['license'])->getValue();
      $license = $license[0]['license'];
      unset($settings['license']);
    }
    // Fetch the $attributes value, if specified.
    if (!empty($settings['attribution'])) {
      $attributes = $entity->get($settings['attribution'])->getValue();
      $attributes = $attributes[0]['value'];
      unset($settings['attribution']);
    }
    // Fetch the logo value, if specified.
    if (!empty($settings['logo'])) {
      $logo = $entity->get($settings['logo'])->getValue();
      $logo = $logo[0]['value'];
      unset($settings['logo']);
    }
    // Loop through the settings to generate metadata.
    foreach ($settings as $key => $setting) {
      $value = $entity->get($setting)->getValue();
      $metadata[] = array(
        'label' => $key,
        'value' => $value[0]['value'],
      );
    }
    // Create the resource URL.
    $resource_url = $iiif_image_server_location . $image_path[1];

    // Set the resource url as canvas and manifest ID.
    $id = $canvas_id = $resource_url;

    // Create an instance of SharedCanvasManifest class.
    $manifest = new SharedCanvasManifest($id, $label, $description, $attributes, $license, $logo, $metadata);

    // Create canvas.
    $canvas = new Canvas($canvas_id, $label);
    $canvas->setImage($resource_url, $resource_url, $resource_url, $mime_type, $width, $height);
    $manifest->addCanvas($canvas);

    $sc_manifest = $manifest->getManifest();
    return new JsonResponse($sc_manifest);
  }

}
