<?php

namespace Drupal\ar\Controller;

use Drupal\Core\Render\Markup;
use Drupal\Core\Controller\ControllerBase;

/**
 * This is our ar controller
 */
class ArController
{

    public function arText()
    {

        return[
        '#markup' => 'Hello! You can add here a photo of your cat.'
        ];
    }

    public function content()
    {
        $simpleform = \Drupal::formBuilder()
        ->getForm('\Drupal\ar\Form\ArForm');

        return $simpleform;
    }
}
