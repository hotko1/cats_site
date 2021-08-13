<?php

namespace Drupal\ar\Plugin\Block;

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

    foreach ($result as $rows => $content) {
      $rows[] = [
        'data' => [
          $content->id,
          $content->name,
          $content->email_user,
          $content->image,
          $content->time,
        ],
      ];
    }

    $header = ['id', 'name', 'email_user', 'image', 'time'];
    $output = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $output;

//    $cats = [
//      [
//        'name_cat' => 'name',
//        'email' => 'email_user',
//        'image_cat' => 'image',
//        'current_time' => 'time',
//      ],
//    ];

//    $table = [
//      '#type' => 'table',
//      '#header' => [
//        $this->t('Cat name'),
//        $this->t('User email'),
//        $this->t('Photo'),
//        $this->t('Time to add an entry.'),
//      ],
//    ];

//    foreach ($cats as $cat) {
//      $table[] = [
//        'name_cat' => [
//          '#type' => 'markup',
//          '#markup' => $cat['name_cat'],
//        ],
//        'email' => [
//          '#type' => 'markup',
//          '#markup' => $cat['email'],
//        ],
//        'image_cat' => [
//          '#type' => 'markup',
//          '#markup' => $cat['image_cat'],
//        ],
//        'current_time' => [
//          '#type' => 'markup',
//          '#markup' => $cat['current_time'],
//        ],
//      ];
//    }

//    return $table;
  }

}
