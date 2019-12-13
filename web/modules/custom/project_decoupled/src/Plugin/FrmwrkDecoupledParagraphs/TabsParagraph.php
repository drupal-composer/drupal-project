<?php

namespace Drupal\project_decoupled\Plugin\FrmwrkDecoupledParagraphs;

use Drupal\frmwrk_decoupled_paragraphs\FrmwrkDecoupledParagraphsPluginBase;

/**
 * Plugin implementation of the Tabs paragraph type.
 *
 * @FrmwrkDecoupledParagraphs(
 *   id = "tabs",
 *   description = @Translation("Adds tabbed groups for content.")
 * )
 *
 * @package Drupal\frmwrk_decoupled_paragraphs\Plugin\FrmwrkDecoupledParagraphs
 */
class TabsParagraph extends FrmwrkDecoupledParagraphsPluginBase {

  /**
   * {@inheritdoc}
   */
  public function addParagraphs(): array {
    // All paragraphs get their id and parent field name added to them so no
    // need to add it again.
    return [
      // Paragraph type.
      'TabsParagraph' => [
        // Drupal field machine name => GraphQL resolver plugin id.
        'field_tab' => 'paragraph_paragraph_field_resolver',
        'field_style_options' => 'paragraph_paragraph_field_resolver',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getParagraphTypeResolvers(): array {
    return [
      // Drupal paragraph type => graphql interface implementation.
      'tabs' => 'TabsParagraph',
    ];
  }

}
