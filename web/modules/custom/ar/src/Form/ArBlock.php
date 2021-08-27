<?php

namespace Drupal\ar\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Provides a block called "Example ar block".
 */
class ArBlock extends Database {

  /**
   * {@inheritdoc}
   */
  public function build() {

//    $result = \Drupal::database()->select('ar', 'n')
//      ->fields('n', ['id', 'name', 'email_user', 'fid', 'time'])
//      ->execute()->fetchAllAssoc('id');
    $query = \Drupal::database()->select('ar', 'n');
    $query->fields('n', ['id', 'name', 'email_user', 'fid', 'time']);
    $result = $query->execute()->fetchAll();

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

      $url_delete = Url::fromRoute('ar.delete_form', ['id' => $data->id], []);
      $text_delete = t('Delete');
      $link_delete = Link::fromTextAndUrl($text_delete, $url_delete);

      $url_edit = Url::fromRoute('ar.edit_form', ['id' => $data->id], []);
      $text_edit = t('Edit');
      $link_edit = Link::fromTextAndUrl($text_edit, $url_edit);

      $rows[] = [
        'name' => $data->name,
        'email_user' => $data->email_user,
        'fid' => $link_markup,
        'time' => $timeout,
        'delete' => $link_delete,
        'edit' => $link_edit,
      ];
    }

    $revers = array_reverse($rows);

    $header = [
      'name' => t('Name'),
      'email_user' => t('Email user'),
      'fid' => t('Image'),
      'time' => t('Time'),
      'delete' => t('Delete'),
      'edit' => t('Edit'),
    ];
    $output = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $revers,
    ];

    return $output;
  }

}
