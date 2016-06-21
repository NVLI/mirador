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
  function __construct($id, $label, $description, $attributes, $rights, $author, $date) {
    $this->id = $id;
    $this->object_label = $label;
    $this->description = $description;
    $this->attributes = $attributes;
    $this->rights = $rights;
    $this->author = $author;
    $this->date = $date;

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
      'author' => $this->author,
      'date' => $this->date,
      'sequences' => array( // an array but will always be a single object in our application
        array(
          '@type' => 'sc:Sequence',
          'id' => 'http://dev.llgc.org.uk/iiif/examples/photos/sequence/Physical.json',
          'label' => $this->object_label . ', in order',
          'canvases' => $this->canvases
        )
      )
    );
    return $scManifest;
  }
}
