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

/**
 * Controller routines for page example routes.
 */
class MiradorManifestController extends ControllerBase {

  /**
   * Page callback for getting manifest json.
   */
  public function getManifest($entityType, $fieldName, $entityId) {
    // Load the entity.
    $entity = entity_load('node', $entityId);
    // Get the field value.
    $image = $entity->get($fieldName)->getValue();
    // Load the image and take file uri.
    $fid = $image[0]['target_id'];
    $file = file_load($fid);
    $uri = $file->getFileUri();
    // Exploding the image URI, as the public location is specified in loris server.
    $image_path = explode("public://", $uri);
    // Create the resource URL.
    // @to-do: Create global settings and fetch the loris server from global variable.
    $resource_url = 'http://mirador.local/loris/' . $image_path[1];
    $mimetype = "image/jpg";
    $width = 4217;
    $height = 4217;
    $label = "Test LAbel";
    $id = "http://dev.llgc.org.uk/iiif/examples/photos/manifest.json";
    $canvas_id = "http://dev.llgc.org.uk/iiif/examples/photos/canvas/3891256.json";
    $manifest = new SharedCanvasManifest($id, $label);
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

