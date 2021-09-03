<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ar\Form\ArBlock;

/**
 * This is our cats list controller.
 */
class CatsListController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
//    $simpleform = \Drupal::formBuilder()
//      ->getForm('\Drupal\ar\Form\ArForm');

    $tableOut = new ArBlock();
    $tableOutput = $tableOut->build();

    return [
      '#theme' => 'ar',
//      '#forms' => $simpleform,
      '#tables' => $tableOutput,
      '#title' => $this->t('Cats list.'),
    ];
  }

}
