<?php

namespace Drupal\project_decoupled\Plugin\FrmwrkDecoupledParagraphs;

use Drupal\frmwrk_decoupled_paragraphs\FrmwrkDecoupledParagraphsPluginBase;

/**
 * Plugin implementation of the frmwrk_decoupled_paragraphs.
 *
 * @FrmwrkDecoupledParagraphs(
 *   id = "from_library",
 *   description = @Translation("Adds documentation type paragraphs.")
 * )
 *
 * @package Drupal\frmwrk_decoupled_paragraphs\Plugin\FrmwrkDecoupledParagraphs
 */
class FromLibraryParagraph extends FrmwrkDecoupledParagraphsPluginBase {

  /**
   * {@inheritdoc}
   */
  public function addParagraphs(): array {
    // All paragraphs get their id and parent field name added to them so no
    // need to add it again.
    return [
      // Paragraph type.
      'FromLibraryParagraph' => [
        // Drupal field machine name => GraphQL resolver plugin id.
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getParagraphTypeResolvers(): array {
    return [
      // Drupal paragraph type => graphql interface implementation.
      'from_library' => 'FromLibraryParagraph',
    ];
  }

}
