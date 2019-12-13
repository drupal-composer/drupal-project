<?php

namespace Drupal\project_decoupled\Plugin\GraphQL\SchemaExtension;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\frmwrk_decoupled\FrmwrkDecoupledNodesPluginManager;
use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
use Drupal\graphql\Plugin\GraphQL\SchemaExtension\SdlSchemaExtensionPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProjectNodeSchemaExtension.
 *
 * @SchemaExtension(
 *   id = "project_node_extension",
 *   name = "Project node schema extension",
 *   description = "Extend schema with configuration related to project nodes.",
 *   schema = "frmwrk"
 * )
 */
class ProjectNodeSchemaExtension extends SdlSchemaExtensionPluginBase {

  /**
   * Nodes pluginmanager.
   *
   * @var mixed
   */
  private $nodesPluginManager;

  /**
   * Node type array.
   *
   * @var array
   */
  private $nodeTypes;

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition):ProjectNodeSchemaExtension {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler'),
      $container->get('plugin.manager.frmwrk_decoupled_nodes')
    );
  }

  /**
   * ProjectNodeSchemaExtension constructor.
   *
   * @param mixed $configuration
   *   Configuration.
   * @param mixed $pluginId
   *   Plugin ID.
   * @param mixed $pluginDefinition
   *   Plugin definition.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   Module handler.
   * @param \Drupal\frmwrk_decoupled\FrmwrkDecoupledNodesPluginManager $nodesPluginManager
   *   Nodes plugin manager.
   */
  public function __construct($configuration, $pluginId, $pluginDefinition, ModuleHandlerInterface $moduleHandler, FrmwrkDecoupledNodesPluginManager $nodesPluginManager) {
    $this->nodesPluginManager = $nodesPluginManager;

    parent::__construct($configuration, $pluginId, $pluginDefinition, $moduleHandler);
  }

  /**
   * {@inheritdoc}
   */
  public function registerResolvers(ResolverRegistryInterface $registry): void {
    $builder = new ResolverBuilder();

    $types = $this->nodesPluginManager->getDefinitions();

    $nodeTypes = [];

    foreach ($types as $type => $pluginDefinition) {
      $nodeTypes[$type] = $pluginDefinition['resolver'];
    }

    $this->nodeTypes = $nodeTypes;

    $this->addTeaserResolvers($registry, $builder);
  }

  /**
   * Add from_library paragraph handler.
   *
   * @param \Drupal\graphql\GraphQL\ResolverRegistryInterface $registry
   *   Resolver registry.
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   *   Resolver builder.
   */
  private function addTeaserResolvers(ResolverRegistryInterface $registry, ResolverBuilder $builder): void {
    foreach ($this->nodeTypes as $nodeType) {
      $registry->addFieldResolver($nodeType, 'field_summary',
        $builder->produce('node_field_resolver')
          ->map('entity', $builder->fromParent())
          ->map('field', $builder->fromValue('field_summary'))
      );

      $registry->addFieldResolver($nodeType, 'field_teaser_image',
        $builder->produce('media_file_resolver')
          ->map('entity', $builder->fromParent())
          ->map('field', $builder->fromValue('field_teaser_image'))
      );
    }
  }

}
