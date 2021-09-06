<?php

namespace Drupal\ar\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Render\Element\Tableselect;

/**
 * Provides a block called "Example ar block".
 */
class ArAdminList extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "ar_list";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $result = \Drupal::database()->select('ar', 'n')
      ->fields('n', ['id', 'name', 'email_user', 'fid', 'time'])
      ->execute()->fetchAllAssoc('id');
    $result = array_reverse($result);
    $options = [];

    $header = [
//      'name' => 'Name',
      'name' => $this->t('Name'),
      'email_user' => $this->t('Email user'),
      'fid' => $this->t('Image'),
      'time' => $this->t('Time'),
      'delete' => $this->t('Delete'),
      'edit' => $this->t('Edit'),
    ];
    foreach ($result as $data) {
      $timestamp = $data->time;
      $timeout = gmdate("Y-m-d H:i:s", $timestamp);

      $file = File::load($data->fid);
      $image = $file->createFileUrl();
      $img = '<img src="' . $image . '" width=400 alt="Cat photo" />';
      $render_image = render($img);
      $image_markup = Markup::create($render_image);

      $text_delete = t('Delete');
      $url_delete = Url::fromRoute('ar.delete_form', ['id' => $data->id], []);
      $url_delete->setOptions([
        'attributes' => [
          'class' => ['use-ajax', 'button', 'button--small'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode(['width' => 400]),
        ],
      ]);
      $link_delete = Link::fromTextAndUrl($text_delete, $url_delete);

      $text_edit = t('Edit');
      $url_edit = Url::fromRoute('ar.edit_form', ['id' => $data->id], []);
      $url_edit->setOptions([
        'attributes' => [
          'class' => ['use-ajax', 'button', 'button--small'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode(['width' => 400]),
        ],
      ]);
      $link_edit = Link::fromTextAndUrl($text_edit, $url_edit);

//      global $_link_delete_global;
//      global $_link_edit_global;

      $options[$data->id] = [
        'name' => $data->name,
        'email_user' => $data->email_user,
        'fid' => $image_markup,
        'time' => $timeout,
        'delete' => $link_delete,
        'edit' => $link_edit,
      ];

    }

    $form['table'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
