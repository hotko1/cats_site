<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ar\Form\ArBlock;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * This is our arModal controller.
 */
class ArModalController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function modal() {
    $options = [
      'dialogClass' => 'popup-dialog-class',
      'width' => '50%',
    ];
    $modalResponse = new AjaxResponse();
    $modalResponse->addCommand(new OpenModalDialogCommand(t('Modal title'), t('The modal text'), $options));

    return $modalResponse;
  }

}
