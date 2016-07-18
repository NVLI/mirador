<?php

/**
 * @file
 * Contains \Drupal\mirador_annotation\Controller\MiradorAnnotationController.
 */

namespace Drupal\mirador_annotation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller routines for mirador annotations creation.
 */
class MiradorAnnotationController extends ControllerBase {

  /**
   * Page Callback: Search Annotation.
   */
  public function searchAnnotation($image_entity_id) {
    $output = array();
    $output = $this->getAnnotation($image_entity_id);
    return new JsonResponse($output);
  }

  /**
   * Returns the annotations list.
   */
  public function getAnnotation($image_id) {
    $annotations = array();
    // Load the mirador global settings.
    $config = \Drupal::config('mirador.settings');
    // Annotation settings.
    $annotation_entity = $config->get('annotation_entity');
    $annotation_text = $config->get('annotation_text');
    $annotation_viewport = $config->get('annotation_viewport');
    $annotation_image_entity = $config->get('annotation_image_entity');
    $annotation_image_resource = $config->get('annotation_resource');

    // Fetch the annotation entity_ids.
    $query = \Drupal::entityQuery($annotation_entity)
      ->condition("$annotation_image_entity.entity.nid", $image_id);
    $annotation_ids = $query->execute();

    // Load the annotations.
    $annotation_data = entity_load_multiple($annotation_entity, $annotation_ids);

    $options = array('absolute' => TRUE);
    foreach ($annotation_data as $annotation) {
      $node_url = \Drupal\Core\Url::fromRoute("entity.$annotation_entity.canonical", ["$annotation_entity" => $annotation->nid->value], $options);
      $node_url = $node_url->toString();
      $resource[] = array(
        '@type' => 'dctypes:Text',
        'id' => $annotation->nid->value,
        'format' => 'text/html',
        'chars' => $annotation->$annotation_text->value,
      );
      $on = array(
        '@id' => $annotation->nid->value,
        '@type' => 'oa:SpecificResource',
        'selector' => array(
          '@id' => $annotation->nid->value,
          '@type' => 'oa:FragmentSelector',
          'value' => $annotation->$annotation_viewport->value,
        ),
        "full" => $annotation->$annotation_image_resource->value,
      );
      $annotations[] = array(
        '@id' => $annotation->nid->value,
        '@type' => 'oa:Annotation',
        'motivation' => array('oa:commenting'),
        'resource' => $resource,
        'on' => $on,
      );
    }
    return $annotations;
  }

}
