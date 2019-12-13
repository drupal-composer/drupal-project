<?php

namespace Drupal\project_decoupled\Plugin\FrmwrkDecoupledParagraphs;

use Drupal\frmwrk_decoupled_paragraphs\FrmwrkDecoupledParagraphsPluginBase;

/**
 * Plugin implementation of the frmwrk_decoupled_paragraphs.
 *
 * @FrmwrkDecoupledParagraphs(
 *   id = "list_filter_options",
 *   description = @Translation("Adds content type paragraphs.")
 * )
 *
 * @package Drupal\frmwrk_decoupled_paragraphs\Plugin\FrmwrkDecoupledParagraphs
 */
class ListFilterOptionsParagraph extends FrmwrkDecoupledParagraphsPluginBase {

  /**
   * {@inheritdoc}
   */
  public function addParagraphs(): array {
    // All paragraphs get their id and parent field name added to them so no
    // need to add it again.
    return [
      // Paragraph type.
      'ListFilterOptionsParagraph' => [
        // Drupal field machine name => GraphQL resolver plugin id.
        'field_filter_limit' => 'paragraph_field_resolver',
        'field_filter_offset' => 'paragraph_field_resolver',
        'field_filter_column' => 'paragraph_field_resolver',
        'field_sorting_direction' => 'paragraph_field_resolver',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getParagraphTypeResolvers(): array {
    return [
      // Drupal paragraph type => graphql interface implementation.
      'list_filter_options' => 'ListFilterOptionsParagraph',
    ];
  }

}
