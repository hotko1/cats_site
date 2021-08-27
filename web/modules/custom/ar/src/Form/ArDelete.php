<?php

namespace Drupal\ar\Form;

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
    return t('Do you wqnt to delete data number %id ?', ['%id' => $this->id]);
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

//  /**
//   * {@inheritdoc}
//   */
//  public function validateForm(array &$form, FormStateInterface $form_state) {
//    parent::validateForm($form, $form_state);
//  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('ar')
      ->condition('id', $this->id)
      ->execute();
    \Drupal::messenger()->addStatus('Succesfully deleted.');
    $form_state->setRedirect('ar.artext');
  }

}
