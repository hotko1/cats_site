<?php

namespace Drupal\ar\Controller;

//use Drupal\ar\Form\ArAdminList;
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

    return $simpleform;
  }

}
