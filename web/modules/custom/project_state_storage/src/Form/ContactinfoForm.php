<?php

namespace Drupal\project_state_storage\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\project_state_storage\StateStorageForm;

/**
 * Configure project contact info State Storage settings for this site.
 */
class ContactinfoForm extends StateStorageForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'project_state_storage_contactinfo';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['address']['fullstreet'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Street + nr'),
      '#default_value' => $this->getStateValue('fullstreet', '', 'contactinfo'),
    ];

    $form['address']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $this->getStateValue('city', '', 'contactinfo'),
    ];

    $form['address']['zipcode'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Zipcode'),
      '#default_value' => $this->getStateValue('zipcode', '', 'contactinfo'),
    ];

    $form['address']['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#default_value' => $this->getStateValue('phone', '', 'contactinfo'),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
      '#button_type' => 'primary',
    ];

    // By default, render the form using system-config-form.html.twig.
    $form['#theme'] = 'system_config_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $stateValues = $this->extractFormStateValues($form_state);

    $this->setState($stateValues, 'contactinfo');
  }

}
