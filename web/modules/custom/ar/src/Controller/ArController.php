<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Render\Markup;
use Drupal\Core\Controller\ControllerBase;
//use Drupal\ar\Form\ArBlock;

/**
 * This is our ar controller.
 */
class ArController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $simpleform = \Drupal::formBuilder()
      ->getForm('\Drupal\ar\Form\ArForm');

//    $tableOut = new ArBlock();
//    $tableOutput = $tableOut->build();
//
//    return [$simpleform, $tableOutput];

    return $simpleform;
  }

}
