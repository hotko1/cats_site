<?php

namespace Drupal\ar\Controller;

/**
 * This is our ar controller
 */
class ArController{

  public function arText(){
    $cats = [
      'Hello! You can add here a photo of your cat.'
    ];

    $ourAres = '';
    foreach ($cats as $ar){
      $ourAres .= '<p>' . $ar . '</p>';
    }

    return[
      '#type' => 'markup',
      '#markup' =>  $ourAres,
    ];
  }
}
