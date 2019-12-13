<?php

namespace Drupal\project_decoupled\Plugin\GraphQL\DataProducer;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;

/**
 * Class StateValueResolver.
 *
 * @DataProducer(
 *   id = "state_value_resolver",
 *   name = @Translation("State value resolver"),
 *   description = @Translation("Resolve a specific state value."),
 *   produces = @ContextDefinition("string",
 *     label = @Translation("state value.")
 *   ),
 *   consumes = {
 *     "collection" = @ContextDefinition("list",
 *       label = @Translation("State collection"),
 *       required = TRUE
 *     ),
 *     "key" = @ContextDefinition("string",
 *       label = @Translation("State key"),
 *       required = TRUE
 *     ),
 *   }
 * )
 *
 * @package Drupal\project_decoupled\Plugin\GraphQL\DataProducer
 */
class StateValueResolver extends DataProducerPluginBase {

  /**
   * Resolve paragraph array field returning the contained props.
   *
   * @param array $collection
   *   State value collection.
   * @param string $key
   *   State entry to retrieve.
   * @param \Drupal\Core\Cache\RefinableCacheableDependencyInterface $metadata
   *   Cache metadata.
   *
   * @return string
   *   State value.
   */
  public function resolve(array $collection, string $key, RefinableCacheableDependencyInterface $metadata): string {
    return isset($collection[$key]) ? $collection[$key] : "";
  }

}
