<?php

/**
 * @file
 * Contains \Drupal\mirdador\Controller\MiradorManifestController.
 */

namespace Drupal\mirador\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\user\Entity\User;
use \Drupal\file\Entity\File;
use Drupal\mirador\SharedCanvasManifest;
use Drupal\mirador\Canvas;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Serialization\Json;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Controller routines for page example routes.
 */
class MiradorManifestController extends ControllerBase {

  /**
   * Page callback: Returns manifest json.
   */
  public function getManifest($entityType, $fieldName, $entityId, $settings) {
    // Set a default label and description, if none specified by the user.
    $label = $description = t("Mirador Viewer");
    // Set a default value for width and height, if none specified by the user.
    $width = $height = 4217;
    $author = $rights = $attributes = $date = NULL;

    // Fetch the IIIF image server location from settings.
    $config = \Drupal::config('mirador.settings');
    $iifImageServerLocation = $config->get('iiif_server');
    // @to-do: Display a message if no server specified.

    // Unserialize the settings to get the settings array.
    $settings = unserialize($settings);

    // Load the entity.
    // Assuming that the mirodar viewer will be attached always with node
    // entity type.
    $entity = entity_load('node', $entityId);
    // Get the image field value.
    $image = $entity->get($fieldName)->getValue();
    // Load the image and take file uri.
    $fid = $image[0]['target_id'];
    $file = file_load($fid);
    $uri = $file->getFileUri();

    // Exploding the image URI, as the public location
    // will be specified in IIF Server.
    $imagePath = explode("public://", $uri);

    // Fetch the label, if specified.
    if (!empty($settings['label'])) {
      $label = $entity->get($settings['label'])->getValue();
      $label = $label[0]['value'];
    }
    // Fetch the description, if specified.
    if (!empty($settings['description'])) {
      $description = $entity->get($settings['description'])->getValue();
      $description = $description[0]['value'];
    }
    // Fetch the width, if specified.
    if (!empty($settings['width'])) {
      $width = $settings['width'];
    }
    // Fetch the height, if specified.
    if (!empty($settings['height'])) {
      $height = $settings['height'];
    }
    // Fetch the author, if specified.
    if (!empty($settings['author'])) {
      $author = $entity->get($settings['author'])->getValue();
      $author = $author[0]['value'];
    }
    // Fetch the rights value, if specified.
    if (!empty($settings['rights'])) {
      $rights = $entity->get($settings['rights'])->getValue();
      $rights = $rights[0]['value'];
    }
    // Fetch the $attributes value, if specified.
    if (!empty($settings['attribution'])) {
      $attributes = $entity->get($settings['attribution'])->getValue();
      $attributes = $attributes[0]['value'];
    }
    // Fetch the dates value, if specified.
    if (!empty($settings['date'])) {
      $date = $entity->get($settings['date'])->getValue();
      $date = $date[0]['value'];
    }

    // Create the resource URL.
    $resource_url = $iifImageServerLocation . $imagePath[1];
    $mimetype = "image/jpg";

    $id = "http://dev.llgc.org.uk/iiif/examples/photos/manifest.json";
    $canvas_id = "http://dev.llgc.org.uk/iiif/examples/photos/canvas/3891256.json";

    // Create an instance of SharedCanvasManifest class.
    $manifest = new SharedCanvasManifest($id, $label, $description, $attributes, $rights, $author, $date);

    // Create canvas.
    $canvas = new Canvas($canvas_id, $label);
    $canvas->setImage($resource_url, $resource_url, $resource_url, $mimetype, $width, $height);
    $manifest->addCanvas($canvas);

    $sc_manifest = $manifest->getManifest();
    $this->getJsonResponse($sc_manifest); exit();
  }

  /**
  * Serves common json response.
  *
  * @param $data
  *   Response array for given request.
  *
  * @return JSON
  *   The JSON array for the response.
  */
  public function getJsonResponse($data) {
    $response = new Response();
    if (!empty($data)) {
      $response->setContent(Json::encode($data));
      $response->setStatusCode(Response::HTTP_OK);
      $response->headers->set('Content-Type', 'application/json');
      // prints the HTTP headers followed by the content
      return $response->send();
    }
  }

}

