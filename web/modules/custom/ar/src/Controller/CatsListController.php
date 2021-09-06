<?php

namespace Drupal\ar\Controller;

use Drupal\ar\Form\ArAdminList;
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

//    $_link_delete_all = new ArAdminList();
//    $link_all = $_link_delete_all->deleteAll();

//    return [$simpleform, $link_all];
    return $simpleform;
  }

}
