<?php

namespace Drupal\ar\Form;

use Drupal\Core\Database\Database;

/**
 * Provides a block called "Example ar block".
 */
class ArBlock extends Database {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $result = \Drupal::database()->select('ar', 'n')
      ->fields('n', ['id', 'name', 'email_user', 'image', 'time'])
      ->execute()->fetchAllAssoc('id');

    $rows = [];

    foreach ($result as $data) {
      $timestamp = $data->time;
      $timeout = gmdate("Y-m-d H:i:s", $timestamp);
//      $img = theme_image('image', [
//        'path' => 'public://images',
//      ]);
//      $img = '<img src"' . $this->configuration['image']['value'] . '">';
      $rows[] = [
//        'id' => $data->id,
        'name' => $data->name,
        'email_user' => $data->email_user,
        'image' => $data->image,
        'time' => $timeout,
      ];
    }

    $header = [
//      'id' => 'ID',
      'name' => 'Name',
      'email_user' => 'Email user',
      'image' => 'Image',
      'time' => 'Time',
    ];
    $output = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $output;
  }

}
