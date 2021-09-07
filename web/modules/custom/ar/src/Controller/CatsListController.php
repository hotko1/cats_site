<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * This is our ar controller.
 */
class CatsListController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $simpleform = \Drupal::formBuilder()
      ->getForm('\Drupal\ar\Form\ArAdminList');

    return [
      '#theme' => 'ar-admin',
      '#forms' => $simpleform,
    ];
  }

}
