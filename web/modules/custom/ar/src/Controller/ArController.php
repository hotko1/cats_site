<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Render\Markup;
use Drupal\Core\Controller\ControllerBase;
use Drupal\ar\Form\ArBlock;

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

    $tableOut = new ArBlock();
    $tableOutput = $tableOut->build();

    return [
      '#theme' => 'ar',
      '#forms' => $simpleform,
      '#tables' => $tableOutput,
//      '#items' => $tableOutput,
//      '#items' => [$simpleform, $tableOutput],
//      '#items' => [
//        '#forms' => $simpleform,
//        '#tables' => $tableOutput,
//      ],
      '#title' => 'Hello! You can add here a photo of your cat.',
    ];

//    return [$simpleform, $tableOutput, $templates];
  }

}
