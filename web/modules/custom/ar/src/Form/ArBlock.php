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
      $img = '<img class="image-cat" src="' . $url . '" alt="Cat photo" />';
      $image_link = '<a class="link-image" href="' . $url . '" target="_blank">' . $img . '</a>';
      $render_link = render($image_link);
      $link_markup = Markup::create($render_link);

      $text_delete = t('Delete');
      $url_delete = Url::fromRoute('ar.delete_form', ['id' => $data->id], []);
      $url_delete->setOptions([
        'attributes' => [
          'class' => ['use-ajax', 'button', 'button--small'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode(['width' => 900]),
        ],
      ]);
      $link_delete = Link::fromTextAndUrl($text_delete, $url_delete);

      $text_edit = t('Edit');
      $url_edit = Url::fromRoute('ar.edit_form', ['id' => $data->id], []);
//      $url_edit->setOptions([
//        'attributes' => [
//          'class' => ['use-ajax', 'button', 'button--small'],
//          'data-dialog-type' => 'modal',
//          'data-dialog-options' => Json::encode(['width' => 400]),
//        ],
//      ]);
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

    return $revers;
  }

}
