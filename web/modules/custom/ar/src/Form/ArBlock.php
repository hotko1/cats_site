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

      $domen = $_SERVER['SERVER_NAME'];
      if (isset($_SERVER['HTTPS'])) {
        $protocol = 'https:';
      }
      else {
        $protocol = 'http:';
      }
      $url = "{$protocol}//{$domen}{$image}";

      $img = '<img src="' . $url . '" alt="Cat photo" />';
      $image_link = '<a href="' . $url . '" target="_blank">' . $img . '</a>';
      $render_link = render($image_link);
      $link_markup = Markup::create($render_link);

      $rows[] = [
        'name' => $data->name,
        'email_user' => $data->email_user,
        'fid' => $link_markup,
        'time' => $timeout,
      ];
    }

    $revers = array_reverse($rows);

    $header = [
      'name' => t('Name'),
      'email_user' => t('Email user'),
      'fid' => t('Image'),
      'time' => t('Time'),
    ];
    $output = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $revers,
    ];

    return $output;
  }

}
