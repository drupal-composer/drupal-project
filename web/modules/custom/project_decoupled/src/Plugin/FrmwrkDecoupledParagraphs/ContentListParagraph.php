<?php

namespace Drupal\project_decoupled\Plugin\FrmwrkDecoupledParagraphs;

use Drupal\frmwrk_decoupled_paragraphs\FrmwrkDecoupledParagraphsPluginBase;

/**
 * Plugin implementation of the frmwrk_decoupled_paragraphs.
 *
 * @FrmwrkDecoupledParagraphs(
 *   id = "content_list",
 *   description = @Translation("Adds content type paragraphs.")
 * )
 *
 * @package Drupal\frmwrk_decoupled_paragraphs\Plugin\FrmwrkDecoupledParagraphs
 */
class ContentListParagraph extends FrmwrkDecoupledParagraphsPluginBase {

  /**
   * {@inheritdoc}
   */
  public function addParagraphs(): array {
    // All paragraphs get their id and parent field name added to them so no
    // need to add it again.
    return [
      // Paragraph type.
      'ContentListParagraph' => [
        // Drupal field machine name => GraphQL resolver plugin id.
        'field_filter_options' => [
          'target_id' => 'paragraph_field_resolver',
        ],
        'field_content_type' => 'paragraph_field_resolver',
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
      'content_list' => 'ContentListParagraph',
    ];
  }

}
