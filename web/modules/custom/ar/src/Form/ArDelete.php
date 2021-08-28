<?php

namespace Drupal\ar\Form;

use Drupal\file\Entity\File;
use Robo\Collection\Temporary;
use Drupal\Core\Link;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Driver\pgsql\Update;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

/**
 * Add Class ArDelete.
 *
 * @package Drupal\ar\Form
 */
class ArDelete extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public $id;

//  /**
//   * {@inheritdoc}
//   */
//  public $fid;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Delete data');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('ar.artext');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Do you want to delete data number %id ?', [
      '%id' => $this->id,
//      '%fid' => $this->fid,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete it');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

    $this->id = $id;
//    $this->fid = $fid;

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $query = \Drupal::database();
//    $id = $this->id;
//    $fid = $query
//      ->select('ar', 'data')
//      ->condition('id', $id)
//      ->fields('data', ['fid']);
//    $fid = json_decode(json_encode($fid), TRUE);

//    $query = \Drupal::database();
//    $id = $this->id;
//    $fid = $query
//      ->select('ar', 'data')
//      ->condition('id', $id)
//      ->fields('data', $fid);

//    $fid = $this->fid;
//    $fid = $query
//      ->select('file_managed', 'k')
//      ->condition('fid', $fid)
//      ->fields('data', ['fid']);
//    $file = File::load($fid['fid']);
//    $file->setTemporary();

//    $fid = $this->fid;
//    $fid = \Drupal::database()
//    $fid = $this->fid;

//    $fid = $this->fid;
//    $query = \Drupal::database();
//    $fids = $query
//      ->select('ar', 'n')
//      ->condition($fid, 'fid');
//    $rrr = $fids->fields('n', ['id', 'name', 'email_user', 'fid', 'time']);
//    $files = File::load($rrr['fid']);
//    $files->setTemporary();


//    $query = \Drupal::database();
//    $fids = $query
//      ->select('ar', 'n')
//      ->condition($this->id, 'id');
//    $files = File::load($fids['fid']);
//    $files->setTemporary();

//    $querys = \Drupal::database();
//    $querys->delete('file_managed')
//      ->condition('fid', $this->fid, '=')
//      ->execute();

    $query = \Drupal::database();
    $id = $this->id;
    $fid = $query
      ->select('ar', 'data')
      ->condition('id', $id)
      ->fields('data', ['fid'])
      ->execute()->fetchAll();

    $fid = json_decode(json_encode($fid), TRUE);

    $re_fid = $fid[0]['fid'];

//    $files = File::load($fid[0]['fid']);
    $files = File::load($re_fid);
    $files->setTemporary();

    $query->update('file_managed')
      ->condition('fid', $fid)
      ->fields(['status' => 0]);

//    $query = \Drupal::database();
    $query->delete('ar')
      ->condition('id', $this->id)
      ->execute();

//    $file->delete();
//    $querys = \Drupal::database();
//    $querys->update('file_managed')
//      ->condition('fid', $this->fid)
//      ->fields(['status' => 0])
//      ->execute();
//    \Drupal::database()->update('file_managed')
//      ->condition('fid', $this->fid)
//      ->fields(['status' => 0]);

    \Drupal::messenger()->addStatus('Succesfully deleted.');
    $form_state->setRedirect('ar.artext');
  }

}
