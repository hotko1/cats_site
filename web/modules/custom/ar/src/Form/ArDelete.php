<?php

namespace Drupal\ar\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
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

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $query = \Drupal::database();
    $id = $this->id;
    $fid = $query
      ->select('ar', 'data')
      ->condition('id', $id)
      ->fields('data', ['fid'])
      ->execute()->fetchAll();
    $fid = json_decode(json_encode($fid), TRUE);

    foreach ($fid as $key) {
      $key = $key['fid'];
      $querys = \Drupal::database();
      $querys->update('file_managed')
        ->condition('fid', $key)
        ->fields(['status' => '0'])
        ->execute();
    }

    $query = \Drupal::database();
    $query->delete('ar')
      ->condition('id', $this->id)
      ->execute();

    \Drupal::messenger()->addStatus('Successfully deleted.');
//    $form_state->setRedirect('ar.artext');

    $response->addCommand(new RedirectCommand('cats'));
    return $response;
  }

}
