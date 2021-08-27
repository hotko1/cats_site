<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ar\Form\ArBlock;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

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

//    $options = [
//      'dialogClass' => 'popup-dialog-class',
//      'width' => '50%',
//    ];
//    $modalResponse = new AjaxResponse();
//    $modalResponse->addCommand(new OpenModalDialogCommand(t('Modal title'), t('The modal text'), $options));

    return [
      '#theme' => 'ar',
      '#forms' => $simpleform,
      '#tables' => $tableOutput,
//      '#modal' => $modalResponse,
      '#title' => 'Hello! You can add here a photo of your cat.',
    ];
  }

}
