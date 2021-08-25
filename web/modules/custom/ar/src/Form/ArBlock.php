<?php

namespace Drupal\ar\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Link;
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
      $link = $_SERVER['SERVER_NAME'];
      $url = "http://{$link}{$image}";
      $image_link = '<a href="' . $url . '" target="_blank">' . $image_markup . '</a>';
      $render_link = render($image_link);
      $link_markup = Markup::create($render_link);

//      $url = 'http://{$link}{$image}';
//      $render_url = render($url);
//      $url_markup = Markup::create($render_url);

//      $link = Link::fromTextAndUrl($image_markup, $url);
//      $link = $link->toRenderable();
//      $render_link = render($link);

//      $url = $data->get('fid')->getValue();

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
