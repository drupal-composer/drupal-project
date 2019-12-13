<?php

namespace Drupal\project_decoupled\Plugin\FrmwrkDecoupledParagraphs;

use Drupal\frmwrk_decoupled_paragraphs\FrmwrkDecoupledParagraphsPluginBase;

/**
 * Plugin implementation of the frmwrk_decoupled_paragraphs.
 *
 * @FrmwrkDecoupledParagraphs(
 *   id = "documentation",
 *   description = @Translation("Adds documentation type paragraphs.")
 * )
 *
 * @package Drupal\frmwrk_decoupled_paragraphs\Plugin\FrmwrkDecoupledParagraphs
 */
class DocumentationParagraph extends FrmwrkDecoupledParagraphsPluginBase {

  /**
   * {@inheritdoc}
   */
  public function addParagraphs(): array {
    // All paragraphs get their id and parent field name added to them so no
    // need to add it again.
    return [
      // Paragraph type.
      'DocumentationParagraph' => [
        // Drupal field machine name => GraphQL resolver plugin id.
        'field_file' => 'media_file_resolver',
        'field_content' => 'paragraph_field_resolver',
        'field_style_options' => 'paragraph_paragraph_field_resolver',
        'field_title' => 'paragraph_field_resolver',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getParagraphTypeResolvers(): array {
    return [
      // Drupal paragraph type => graphql interface implementation.
      'documentation' => 'DocumentationParagraph',
    ];
  }

}
