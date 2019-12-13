<?php

namespace Drupal\project_decoupled\Plugin\GraphQL\DataProducer;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StateCollectionResolver.
 *
 * @DataProducer(
 *   id = "state_collection_resolver",
 *   name = @Translation("State collection resolver"),
 *   description = @Translation("Resolve a specific state collection."),
 *   produces = @ContextDefinition("list",
 *     label = @Translation("state collection.")
 *   ),
 *   consumes = {
 *     "key" = @ContextDefinition("string",
 *       label = @Translation("State key"),
 *       required = TRUE
 *     ),
 *   }
 * )
 *
 * @package Drupal\project_decoupled\Plugin\GraphQL\DataProducer
 */
class StateCollectionResolver extends DataProducerPluginBase implements ContainerFactoryPluginInterface {

  /**
   * State object.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  private $state;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('state')
    );
  }

  /**
   * StateCollectionResolver constructor.
   *
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_configuration, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_configuration);
    $this->state = $state;
  }

  /**
   * Resolve paragraph array field returning the contained props.
   *
   * @param string $key
   *   State entry to retrieve.
   * @param \Drupal\Core\Cache\RefinableCacheableDependencyInterface $metadata
   *   Cache metadata.
   *
   * @return array
   *   State collection.
   */
  public function resolve(string $key, RefinableCacheableDependencyInterface $metadata): array {
    return $this->state->get($key, []);
  }

}
