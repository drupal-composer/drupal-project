<?php

namespace Drupal\project_state_storage;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base state storage form.
 */
abstract class StateStorageForm extends FormBase {

  /**
   * Drupal state object.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  public $state;

  /**
   * Create form.
   *
   * @inheritDoc
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state')
    );
  }

  /**
   * StateStorageForm constructor.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   State.
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * Get the state object from converted form id.
   *
   * @param string $id
   *   Id of the object.
   *
   * @return mixed
   *   Return state config object.
   */
  public function getState($id = NULL) {
    if (!$id) {
      $id = $this->getConvertFormId();
    }

    return $this->state->get($id);
  }

  /**
   * Get the state value from state.
   *
   * @param string $key
   *   The value from the state array to retrieve.
   * @param string $default
   *   The default value on this key.
   * @param string $id
   *   Id of the state object.
   *
   * @return mixed
   *   Return state config value
   */
  public function getStateValue(string $key, $default = '', $id = NULL) {
    $state = $this->getState($id);

    if (!isset($state[$key])) {
      return $default;
    }

    return $state[$key];
  }

  /**
   * Extract forma values from $form_state.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Massage form state values.
   *
   * @return array
   *   Return state values from form.
   */
  public function extractFormStateValues(FormStateInterface $form_state) {
    $form_state->cleanValues();

    foreach ($form_state->getValues() as $keys => $value) {
      $stateValues[$keys] = $value;
    }

    return $stateValues;
  }

  /**
   * Set config state.
   *
   * @param mixed $state
   *   State values.
   * @param string $id
   *   Set form id.
   */
  public function setState($state, $id = NULL) {
    if (!$id) {
      $id = $this->getConvertFormId();
    }

    $this->state->set($id, $state);
  }

  /**
   * Convert form id to usable state name.
   *
   * @return string|string[]
   *   Return converted form id.
   */
  private function getConvertFormId() {
    return str_replace('_', '', ucwords($this->getFormId(), '_'));
  }

}
