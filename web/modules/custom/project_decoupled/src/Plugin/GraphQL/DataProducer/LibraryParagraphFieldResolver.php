<?php

namespace Drupal\project_decoupled\Plugin\GraphQL\DataProducer;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;

/**
 * Class LibraryParagraphFieldResolver.
 *
 * @DataProducer(
 *   id = "library_paragraph_field_resolver",
 *   name = @Translation("library paragraph field resolver"),
 *   description = @Translation("Resolve a specific library item field on a paragraph "),
 *   produces = @ContextDefinition("list",
 *     label = @Translation("Paragraph array field value(s)")
 *   ),
 *   consumes = {
 *     "entity" = @ContextDefinition("any",
 *       label = @Translation("Paragraph"),
 *       required = TRUE
 *     ),
 *   }
 * )
 *
 * @package Drupal\project_decoupled\Plugin\GraphQL\DataProducer
 */
class LibraryParagraphFieldResolver extends DataProducerPluginBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    $a = '';

    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Resolve a libraryitem paragraph.
   *
   * @param mixed $entity
   *   LibraryItem.
   * @param \Drupal\Core\Cache\RefinableCacheableDependencyInterface $metadata
   *   Caching metadata.
   *
   * @return array
   *   Entities array.
   */
  public function resolve($entity, RefinableCacheableDependencyInterface $metadata) {
    if (is_array($entity)) {
      $entity = reset($entity);
    }

    $return = $entity->get('paragraphs')->referencedEntities()[0];

    return $return;
  }

}
