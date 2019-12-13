<?php

namespace Drupal\project_decoupled\Plugin\FrmwrkDecoupledParagraphs;

use Drupal\frmwrk_decoupled_paragraphs\FrmwrkDecoupledParagraphsPluginBase;

/**
 * Plugin implementation of the Tab paragraph type.
 *
 * @FrmwrkDecoupledParagraphs(
 *   id = "tab",
 *   description = @Translation("Adds tabbed content.")
 * )
 *
 * @package Drupal\frmwrk_decoupled_paragraphs\Plugin\FrmwrkDecoupledParagraphs
 */
class TabParagraph extends FrmwrkDecoupledParagraphsPluginBase {

  /**
   * {@inheritdoc}
   */
  public function addParagraphs(): array {
    // All paragraphs get their id and parent field name added to them so no
    // need to add it again.
    return [
      // Paragraph type.
      'TabParagraph' => [
        // Drupal field machine name => GraphQL resolver plugin id.
        'field_tab_content' => 'paragraph_paragraph_field_resolver',
        'field_tab_title' => 'paragraph_field_resolver',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getParagraphTypeResolvers(): array {
    return [
      // Drupal paragraph type => graphql interface implementation.
      'tab' => 'TabParagraph',
    ];
  }

}
