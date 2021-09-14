<?php

namespace Drupal\ar\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Provides a block called "Example ar admin list".
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
      'id' => 'Id',
      'name' => $this->t('Name'),
      'email_user' => $this->t('Email user'),
      'fid' => $this->t('Image'),
      'time' => $this->t('Time'),
      'delete' => $this->t('Delete'),
      'edit' => $this->t('Edit'),
    ];
    foreach ($result as $data) {
      $timestamp = $data->time;
      $timeout = date("Y-m-d H:i:s", $timestamp);

      $file = File::load($data->fid);
      $image = $file->createFileUrl();
      $img = '<img src="' . $image . '" width=400 alt="Cat photo" />';
      $render_image = render($img);
      $image_markup = Markup::create($render_image);

      $text_delete = t('Delete');
      $url_delete = Url::fromRoute('ar.delete_list', ['id' => $data->id], []);
      $url_delete->setOptions([
        'attributes' => [
          'class' => ['use-ajax', 'button', 'button--small'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode(['width' => 400]),
        ],
      ]);
      $link_delete = Link::fromTextAndUrl($text_delete, $url_delete);

      $text_edit = t('Edit');
      $url_edit = Url::fromRoute('ar.edit_list', ['id' => $data->id], []);
      $url_edit->setOptions([
        'attributes' => [
          'class' => ['button', 'button--small'],
          'data-dialog-type' => 'modal',
        ],
      ]);
      $link_edit = Link::fromTextAndUrl($text_edit, $url_edit);

      $_id = $data->id;
      $options[$data->id] = [
        'id' => $_id,
        'name' => $data->name,
        'email_user' => $data->email_user,
        'fid' => $image_markup,
        'time' => $timeout,
        'delete' => $link_delete,
        'edit' => $link_edit,
      ];

      global $_id;
    }

    $form['table'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#empty' => $this->t('No cats found'),
    ];

    $form['delete select'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete selected'),
      '#attributes' => ['onclick' => 'if(!confirm("Do you want to delete data?")){return false;}'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues()['table'];
    $deletes = array_filter($values);

    if ($deletes == NULL) {
      $form_state->setRedirect('ar.structure');
    }
    else {
      $fid = \Drupal::database()->select('ar', 'data')
        ->condition('id', $deletes, 'IN')
        ->fields('data', ['fid'])
        ->execute()->fetchAll();
      $fid = json_decode(json_encode($fid), TRUE);
      foreach ($fid as $key) {
        $key = $key['fid'];
        $query = \Drupal::database();
        $query->update('file_managed')
          ->condition('fid', $key, 'IN')
          ->fields(['status' => '0'])
          ->execute();
      }

      $querys = \Drupal::database();
      $querys->delete('ar')
        ->condition('id', $deletes, 'IN')
        ->execute();
      $this->messenger()->addStatus($this->t('Successfully deleted'));
    }
  }

}
