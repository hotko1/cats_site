<?php

namespace Drupal\ar\Form;

use Drupal\Core\Block\BlockBase;
use mysql_xdevapi\Table;

/**
 * Provides a block called "Example ar block".
 *
 * @Block(
 *   id = "ar_cat",
 *   admin_label = @Translation ("Example ar block")
 * )
 */
class ArBlock extends BlockBase {

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
      $rows[] = [
        'id' => $data->id,
        'name' => $data->name,
        'email_user' => $data->email_user,
        'image' => $data->image,
        'time' => $timeout,
      ];
    }

    $header = [
      'id' => $this->t('ID'),
      'name' => $this->t('Name'),
      'email_user' => $this->t('Email user'),
      'image' => $this->t('Image'),
      'time' => $this->t('Time'),
    ];
    $output = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $output;
  }

}
