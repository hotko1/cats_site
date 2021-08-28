<?php

namespace Drupal\ar\Controller;

//use Drupal\Core\Ajax\AjaxResponse;
//use Drupal\Core\Ajax\OpenModalDialogCommand;
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
      '#title' => 'Hello! You can add here a photo of your cat.',
    ];
  }

}
