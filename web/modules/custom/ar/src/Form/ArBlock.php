<?php

namespace Drupal\ar\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Component\Serialization\Json;

/**
 * Provides a block called "Example ar block".
 */
class ArBlock extends Database {

  /**
   * {@inheritdoc}
   */
  public function build() {
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

      $text_delete = t('Delete');
      $link_url = Url::fromRoute('ar.delete_form', ['id' => $data->id], []);
      $link_url->setOptions([
        'attributes' => [
          'class' => ['use-ajax', 'button', 'button--small'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode(['width' => 400]),
        ],
      ]);
      $link_delete = Link::fromTextAndUrl($text_delete, $link_url);

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
