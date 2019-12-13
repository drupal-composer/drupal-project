<?php

namespace Drupal\project_decoupled\Plugin\FrmwrkDecoupledParagraphs;

use Drupal\frmwrk_decoupled_paragraphs\FrmwrkDecoupledParagraphsPluginBase;

/**
 * Plugin implementation of the frmwrk_decoupled_paragraphs.
 *
 * @FrmwrkDecoupledParagraphs(
 *   id = "usp",
 *   description = @Translation("Adds content type paragraphs.")
 * )
 *
 * @package Drupal\frmwrk_decoupled_paragraphs\Plugin\FrmwrkDecoupledParagraphs
 */
class USPParagraph extends FrmwrkDecoupledParagraphsPluginBase {

  /**
   * {@inheritdoc}
   */
  public function addParagraphs(): array {
    // All paragraphs get their id and parent field name added to them so no
    // need to add it again.
    return [
      // Paragraph type.
      'USPParagraph' => [
        // Drupal field machine name => GraphQL resolver plugin id.
        'field_title' => 'paragraph_field_resolver',
        'field_usp' => 'paragraph_field_resolver',
        'field_usp_link' => 'paragraph_field_resolver',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getParagraphTypeResolvers(): array {
    return [
      // Drupal paragraph type => graphql interface implementation.
      'usp' => 'USPParagraph',
    ];
  }

}
