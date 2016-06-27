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
    $form['iiif_server'] = array(
      '#type' => 'textfield',
      '#title' => t('IIIF Image server location'),
      '#default_value' => $config->get('iiif_server'),
      '#required' => TRUE,
      '#description' => t('Please enter the image server location'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mirador.settings')
      ->set('iiif_server', $form_state->getValue('iiif_server'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

}
