<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Render\Markup;
use Drupal\Core\Controller\ControllerBase;

/**
 * This is our ar controller.
 */
class ArController extends ControllerBase {

  public function content() {
    $simpleform = \Drupal::formBuilder()
      ->getForm('\Drupal\ar\Form\ArForm');

    return $simpleform;
  }

}
