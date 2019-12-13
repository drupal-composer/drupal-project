<?php

namespace Drupal\project_decoupled\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\project_state_storage\StateStorageForm;

/**
 * Configure project_decoupled settings for this site.
 */
class GlobalSiteConfigurationForm extends StateStorageForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'global_site_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['frontend_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Frontend url'),
      '#default_value' => $this->getStateValue('frontend_url'),
    ];

    $form['frontend_base_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Frontend base path'),
      '#desciption' => 'A base path (beginning with a /',
      '#default_value' => $this->getStateValue('frontend_base_path', '/frontend'),
    ];

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save and continue'),
      '#button_type' => 'primary',
      '#submit' => ['::submitForm'],
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strpos($form_state->getValue('frontend_base_path'), '/') !== 0) {
      $form_state->setErrorByName('frontend_base_path', $this->t('Your path does not start with a /'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $stateValues = $this->extractFormStateValues($form_state);

    $this->setState($stateValues);
  }

}
