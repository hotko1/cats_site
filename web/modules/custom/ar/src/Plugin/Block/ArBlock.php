<?php

namespace Drupal\ar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block called "Exaple ar block".
 *
 * @Block(
 *   id = "ar_cat",
 *   admin_label = @Translation("ar")
 * )
 */
class ArBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('The output of cats block.'),
    ];
  }

}
