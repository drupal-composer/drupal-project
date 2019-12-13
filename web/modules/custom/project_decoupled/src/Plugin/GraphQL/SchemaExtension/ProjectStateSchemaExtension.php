<?php

namespace Drupal\project_decoupled\Plugin\GraphQL\SchemaExtension;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
use Drupal\graphql\Plugin\GraphQL\SchemaExtension\SdlSchemaExtensionPluginBase;

/**
 * Class ProjectStateSchemaExtension.
 *
 * @SchemaExtension(
 *   id = "project_state_extension",
 *   name = "Project config extension",
 *   description = "Extend schema with configuration related to project.",
 *   schema = "frmwrk"
 * )
 */
class ProjectStateSchemaExtension extends SdlSchemaExtensionPluginBase {

  /**
   * {@inheritdoc}
   */
  public function registerResolvers(ResolverRegistryInterface $registry): void {
    $builder = new ResolverBuilder();

    $this->addConfigs($registry, $builder);
    $this->addLibraryParagraph($registry, $builder);
  }

  /**
   * Add from_library paragraph handler.
   *
   * @param \Drupal\graphql\GraphQL\ResolverRegistryInterface $registry
   *   Resolver registry.
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   *   Resolver builder.
   */
  private function addLibraryParagraph(ResolverRegistryInterface $registry, ResolverBuilder $builder): void {
    $registry->addFieldResolver('FromLibraryParagraph', 'paragraph',
      $builder->produce('library_paragraph_field_resolver')
        ->map('entity', $builder->produce('entity_reference')
          ->map('entity', $builder->fromParent())
          ->map('field', $builder->fromValue('field_reusable_paragraph'))
          ->map('access', $builder->fromValue(FALSE)))
    );

    $registry->addFieldResolver('USPParagraph', 'field_usp',
      $builder->produce('paragraph_multivalue_field_resolver')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_usp'))
    );

    $registry->addFieldResolver('USPParagraph', 'field_usp_title',
      $builder->produce('paragraph_field_resolver')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_usp_title'))
    );

    $registry->addFieldResolver('USPParagraph', 'field_usp_link',
      $builder->produce('link_object')
        ->map('link', $builder->fromParent())
        ->map('field', $builder->fromValue('field_usp_link'))
    );
  }

  /**
   * Add config fields.
   *
   * @param \Drupal\graphql\GraphQL\ResolverRegistryInterface $registry
   *   Resolver registry.
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   *   Resolver builder.
   */
  private function addConfigs(ResolverRegistryInterface $registry, ResolverBuilder $builder): void {
    $registry->addFieldResolver('Config', 'googleanalytics',
      $builder->produce('state_collection_resolver')
        ->map('key', $builder->fromValue('GoogleAnalytics'))
    );

    $registry->addFieldResolver('googleanalytics', 'id',
      $builder->produce('state_value_resolver')
        ->map('collection', $builder->fromParent())
        ->map('key', $builder->fromValue('id'))
    );

    $registry->addFieldResolver('Query', 'state',
      $builder->produce('state_collection_resolver')
    );

    $registry->addFieldResolver('Config', 'contactinfo',
      $builder->produce('state_collection_resolver')
        ->map('key', $builder->fromValue('contactinfo'))
    );
  }

}
