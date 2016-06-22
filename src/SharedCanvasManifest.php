<?php
/**
 * @file
 * Contains Mirador canvas creator.
 */

namespace Drupal\mirador;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SharedCanvasManifest {
  protected $id = '';
  protected $object_label = '';
  protected $metadata_uri = '';
  protected $canvases = array();

  /**
   * Initiate the manifest
   *
   * @param unknown $metadata_uri
   * @param string $object_label
   */
  function __construct($id, $label, $description, $attributes, $license, $logo, $metadata) {
    $this->id = $id;
    $this->object_label = $label;
    $this->description = $description;
    $this->attributes = $attributes;
    $this->license = $license;
    $this->logo = $logo;
    $this->metadata = $metadata;
  }

  /**
   * Add a $canvas to $sequences
   *
   * @param string $canvas
   */
  function addCanvas($canvas = null) {
    if ($canvas != null) {
      $this->canvases [] = $canvas->toArray();
    }
  }

  /**
   * Build and return a json string based on what we have in the class
   */
  function getManifest() {
    $scManifest = array (
      '@context' => 'http://iiif.io/api/presentation/2/context.json',
      '@id' => $this->id,
      '@type' => 'sc:Manifest',
      'label' => $this->object_label,
      'description' => $this->description,
      'attribution' => $this->attributes,
      'licence' => $this->license,
      'logo' => $this->logo,
      'metadata' => $this->metadata,
      'sequences' => array( // an array but will always be a single object in our application
        array(
          '@type' => 'sc:Sequence',
          'id' => $this->id,
          'label' => $this->object_label . ', in order',
          'canvases' => $this->canvases
        )
      )
    );
    return $scManifest;
  }
}
