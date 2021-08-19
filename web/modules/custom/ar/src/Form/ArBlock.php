<?php

namespace Drupal\ar\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Render\Markup;
use Drupal\file\Entity\File;

/**
 * Provides a block called "Example ar block".
 */
class ArBlock extends Database {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $result = \Drupal::database()->select('ar', 'n')
      ->fields('n', ['id', 'name', 'email_user', 'fid', 'time'])
      ->execute()->fetchAllAssoc('id');

    $rows = [];

    foreach ($result as $data) {
      $timestamp = $data->time;
      $timeout = date("d/m/Y H:i:s", $timestamp);

      $file = File::load($data->fid);
      $image = $file->createFileUrl();
      $img = '<img src="' . $image . '" width=400 alt="Cat photo" />';
      $render_image = render($img);
      $image_markup = Markup::create($render_image);

      $rows[] = [
        'name' => $data->name,
        'email_user' => $data->email_user,
        'fid' => $image_markup,
        'time' => $timeout,
      ];
    }

    $revers = array_reverse($rows);

    $header = [
      'name' => 'Name',
      'email_user' => 'Email user',
      'fid' => 'Image',
      'time' => 'Time',
    ];
    $output = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $revers,
    ];

    return $output;
  }

}
