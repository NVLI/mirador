<?php

/**
 * @file
 * Administrative class form for the mirador module.
 *
 * Contains \Drupal\mirador\Form\MiradorSettingsForm.
 */

namespace Drupal\mirador\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Contribute form.
 */
class MiradorSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mirador_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mirador.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mirador.settings');
    // IIIF image server settings.
    $form['image_server_settings'] = array(
      '#type' => 'details',
      '#title' => t('Image Server Settings'),
      '#open' => TRUE,
    );
    $form['image_server_settings']['iiif_server'] = array(
      '#type' => 'textfield',
      '#title' => t('IIIF Image server location'),
      '#default_value' => $config->get('iiif_server'),
      '#required' => TRUE,
      '#description' => t('Please enter the image server location'),
    );
    $default_endpoint = $config->get('rest_endpoints');
    if (empty($config->get('rest_endpoints'))) {
      $default_endpoint = 1;
    }
    $form['rest_endpoints'] = array(
      '#type' => 'radios',
      '#options' => array(1 => t('Rest Service'), 0 => t('Custom End Point')),
      '#title' => t('Annotation Endpoint'),
      '#default_value' => $default_endpoint,
      '#description' => t('Select the annotation endpoint method'),
    );
    $form['annotation_settings'] = array(
      '#type' => 'details',
      '#title' => t('Annotation Settings'),
      '#open' => FALSE,
      '#prefix' => '<div class = "test">',
      '#suffix' => '</div>'
    );
    $form['annotation_settings']['annotation_entity'] = array(
      '#type' => 'textfield',
      '#title' => t('Entity'),
      '#default_value' => $config->get('annotation_entity'),
      '#size' => 30,
      '#description' => t('The entity to which the annotations should be stored.'),
    );
    $form['annotation_settings']['annotation_bundle'] = array(
      '#type' => 'textfield',
      '#title' => t('Bundle'),
      '#default_value' => $config->get('annotation_bundle'),
      '#size' => 30,
      '#description' => t('The bundle of the entity to which the annotations should be stored.'),
    );
    // Annotation field mapping settings.
    $form['annotation_settings']['annotation_field_mappings'] = array(
      '#type' => 'details',
      '#title' => t('Annotation Field Mapping'),
      '#states' => array(
        'visible' => array(
          ':input[name="rest_endpoints"]' => array('value' => '1'),
        ),
      ),
      '#open' => FALSE,
    );
    $form['annotation_settings']['annotation_field_mappings']['annotation_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation Text'),
      '#default_value' => $config->get('annotation_text'),
      '#size' => 30,
      '#description' => t('The field to which the annotation text to be stored'),
    );
    $form['annotation_settings']['annotation_field_mappings']['annotation_viewport'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation View Port'),
      '#default_value' => $config->get('annotation_viewport'),
      '#size' => 30,
      '#description' => t('The field to which the annotation view port to be stored'),
    );
    $form['annotation_settings']['annotation_field_mappings']['annotation_image_entity'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation Image Entity'),
      '#default_value' => $config->get('annotation_image_entity'),
      '#size' => 30,
      '#description' => t('The entity reference field to the image entity'),
    );
    $form['annotation_settings']['annotation_field_mappings']['annotation_resource'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation Resource'),
      '#default_value' => $config->get('annotation_resource'),
      '#size' => 30,
      '#description' => t('Text field to store the annotation resource'),
    );
    $form['annotation_settings']['annotation_field_mappings']['annotation_language'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation Language'),
      '#default_value' => $config->get('annotation_language'),
      '#size' => 30,
      '#description' => t('Text field to store the annotation language'),
    );

    // Annotation endpoint settings.
    $form['annotation_settings']['annotation_endpoints'] = array(
      '#type' => 'details',
      '#title' => t('Annotation End points'),
      '#states' => array(
        'visible' => array(
          ':input[name="rest_endpoints"]' => array('value' => '0'),
        ),
      ),
      '#open' => FALSE,
    );

    // Create endpoint.
    $form['annotation_settings']['annotation_endpoints']['create'] = array(
      '#type' => 'details',
      '#title' => t('Create End point'),
      '#open' => FALSE,
    );
    $form['annotation_settings']['annotation_endpoints']['create']['annotation_create'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation create endpoint'),
      '#default_value' => $config->get('annotation_create'),
      '#size' => 30,
      '#description' => t('The annotation create endpoint'),
    );
    $form['annotation_settings']['annotation_endpoints']['create']['annotation_create_method'] = array(
      '#type' => 'select',
      '#title' => t('Annotation create method'),
       '#options' => array('POST' => t('POST'), 'GET' => t('GET')),
      '#default_value' => $config->get('annotation_create_method'),
      '#description' => t('The http method used for annotation creation'),
    );

    // Search endpoint.
    $form['annotation_settings']['annotation_endpoints']['search'] = array(
      '#type' => 'details',
      '#title' => t('Search End point'),
      '#open' => FALSE,
    );
    $form['annotation_settings']['annotation_endpoints']['search']['annotation_search'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation search endpoint'),
      '#default_value' => $config->get('annotation_search'),
      '#size' => 30,
      '#description' => t('The annotation search endpoint'),
    );
    $form['annotation_settings']['annotation_endpoints']['search']['annotation_search_method'] = array(
      '#type' => 'select',
      '#title' => t('Annotation search method'),
       '#options' => array('GET' => t('GET')),
      '#default_value' => $config->get('annotation_create_method'),
      '#description' => t('The http method used for annotation creation'),
    );

    // Update endpoint.
    $form['annotation_settings']['annotation_endpoints']['update'] = array(
      '#type' => 'details',
      '#title' => t('Update End point'),
      '#open' => FALSE,
    );
    $form['annotation_settings']['annotation_endpoints']['update']['annotation_update'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation update endpoint'),
      '#default_value' => $config->get('annotation_update'),
      '#size' => 30,
      '#description' => t('The annotation update endpoint'),
    );
    $form['annotation_settings']['annotation_endpoints']['create']['annotation_update_method'] = array(
      '#type' => 'select',
      '#title' => t('Annotation update method'),
       '#options' => array('GET' => t('GET')),
      '#default_value' => $config->get('annotation_update_method'),
      '#description' => t('The http method used for annotation updation'),
    );

    // Delete Eendpoint.
    $form['annotation_settings']['annotation_endpoints']['delete'] = array(
      '#type' => 'details',
      '#title' => t('Delete End point'),
      '#open' => FALSE,
    );
    $form['annotation_settings']['annotation_endpoints']['delete']['annotation_delete'] = array(
      '#type' => 'textfield',
      '#title' => t('Annotation delete endpoint'),
      '#default_value' => $config->get('annotation_delete'),
      '#size' => 30,
      '#description' => t('The annotation delete endpoint'),
    );
    $form['annotation_settings']['annotation_endpoints']['delete']['annotation_delete_method'] = array(
      '#type' => 'select',
      '#title' => t('Annotation delete method'),
       '#options' => array('DELETE' => t('DELETE')),
      '#default_value' => $config->get('annotation_delete_method'),
      '#description' => t('The http method used for annotation deletion'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;
    $config = $this->configFactory->getEditable('mirador.settings');
    if (!empty($form_state->getValue('iiif_server'))) {
      $config->set('iiif_server', $form_state->getValue('iiif_server'));
    }
    if (!empty($form_state->getValue('annotation_entity'))) {
      $config->set('annotation_entity', $form_state->getValue('annotation_entity'));
    }
    if (!empty($form_state->getValue('annotation_bundle'))) {
      $config->set('annotation_bundle', $form_state->getValue('annotation_bundle'));
    }
    if (!empty($form_state->getValue('annotation_text'))) {
      $config->set('annotation_text', $form_state->getValue('annotation_text'));
    }
    if (!empty($form_state->getValue('annotation_viewport'))) {
      $config->set('annotation_viewport', $form_state->getValue('annotation_viewport'));
    }
    if (!empty($form_state->getValue('annotation_image_entity'))) {
      $config->set('annotation_image_entity', $form_state->getValue('annotation_image_entity'));
    }
    if (!empty($form_state->getValue('annotation_resource'))) {
      $config->set('annotation_resource', $form_state->getValue('annotation_resource'));
    }
    if (!empty($form_state->getValue('annotation_language'))) {
      $config->set('annotation_language', $form_state->getValue('annotation_language'));
    }
    if (!empty($form_state->getValue('annotation_create'))) {
      $config->set('annotation_create', $form_state->getValue('annotation_create'));
    }
    if (!empty($form_state->getValue('annotation_create_method'))) {
      $config->set('annotation_create_method', $form_state->getValue('annotation_create_method'));
    }
    if (!empty($form_state->getValue('annotation_search'))) {
      $config->set('annotation_search', $form_state->getValue('annotation_search'));
    }
    if (!empty($form_state->getValue('annotation_search_method'))) {
      $config->set('annotation_search_method', $form_state->getValue('annotation_search_method'));
    }
    if (!empty($form_state->getValue('annotation_update'))) {
      $config->set('annotation_update', $form_state->getValue('annotation_update'));
    }
    if (!empty($form_state->getValue('annotation_update_method'))) {
      $config->set('annotation_update_method', $form_state->getValue('annotation_update_method'));
    }
    if (!empty($form_state->getValue('annotation_delete'))) {
      $config->set('annotation_delete', $form_state->getValue('annotation_delete'));
    }
    if (!empty($form_state->getValue('annotation_delete_method'))) {
      $config->set('annotation_delete_method', $form_state->getValue('annotation_delete_method'));
    }

    // Set default value for annotation endpoints, If none specified.
    if (empty($config->get('annotation_create'))) {
      $config->set('annotation_create', $base_url . '/entity/' . $form_state->getValue('annotation_entity'));
    }
    if (empty($config->get('annotation_create_method'))) {
      $config->set('annotation_create_method', 'POST');
    }
    if (empty($config->get('annotation_search'))) {
      $config->set('annotation_search', $base_url . '/annotation/search/{image_entity_id}');
    }
    if (empty($config->get('annotation_search_method'))) {
      $config->set('annotation_search_method', 'GET');
    }
    if (empty($config->get('annotation_update'))) {
      $config->set('annotation_update', $base_url . '/' . $form_state->getValue('annotation_entity') . '/{annotation_id}');
    }
    if (empty($config->get('annotation_update_method'))) {
      $config->set('annotation_update_method', 'PATCH');
    }
    if (empty($config->get('annotation_delete'))) {
      $config->set('annotation_delete', $base_url . '/' . $form_state->getValue('annotation_entity') . '/{annotation_id}');
    }
    if (empty($config->get('annotation_delete_method'))) {
      $config->set('annotation_delete_method', 'DELETE');
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }
}
