<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ar\Form\ArAdminList;

/**
 * This is our ar controller.
 */
class CatsListController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $tableOut = new ArAdminList();
    $tableOutput = $tableOut->build();

    return $tableOutput;
  }

}
