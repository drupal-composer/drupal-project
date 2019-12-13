<?php

namespace Drupal\project_decoupled\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\project_state_storage\StateStorageForm;

/**
 * Configure project_decoupled settings for this site.
 */
class GoogleAnalyticsForm extends StateStorageForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'project_decoupled_google_analytics';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google Analytics id'),
      '#default_value' => $this->getStateValue('id', '', 'GoogleAnalytics'),
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
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $stageValues = $this->extractFormStateValues($form_state);

    $this->setState($stageValues, 'GoogleAnalytics');
  }

}
