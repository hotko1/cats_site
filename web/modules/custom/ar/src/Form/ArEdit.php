<?php

namespace Drupal\Ar\Form;

use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Use Class ArForm.
 *
 * @package Drupal\Ar\Form
 */
class ArEdit extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ar_form';
  }

  /**
   * {@inheritdoc}
   */
  public $id;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    $conn = Database::getConnection();
    $query = $conn->select('ar', 'n')
      ->condition('id', $id)
      ->fields('n');
    $data = $query->execute()->fetchAssoc();

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>',
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#required' => TRUE,
      '#default_value' => (isset($data['name'])) ? $data['name'] : '',
      '#attributes' => [
        'placeholder' => $this->t('The length of the name is 2-32 letters.'),
      ],
    ];

    $form['email_message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="email-result_message"></div>',
    ];

    $form['email_user'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your email:'),
      '#required' => TRUE,
      '#default_value' => (isset($data['email_user'])) ? $data['email_user'] : '',
      '#attributes' => [
        'placeholder' => $this->t('Only Latin letters, "_" and "-".'),
      ],
      '#ajax' => [
        'callback' => '::mailValidateCallback',
        'event' => 'keyup',
      ],
    ];

    $form['fid_message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="fid-result_message"></div>',
    ];

    $form['fid'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Download image'),
      '#required' => TRUE,
      '#default_value' => [$data['fid']],
      '#description' => $this->t('Image should be less than 2 MB and in JPEG, JPG or PNG format.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2097152],
      ],
      '#upload_location' => 'public://images',
    ];

    global $_global_fid;
    $_global_fid = $data['fid'];

    $form['submit'] = [
      '#type' => 'button',
      '#value' => $this->t('Edit data cat'),
      '#ajax' => [
        'callback' => '::setMessage',
        'event' => 'click',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function mailValidateCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if (!preg_match('/^[a-z._@-]{0,100}$/', $form_state->getValue('email_user'))) {
      $response->addCommand(
        new HtmlCommand(
          '.email-result_message',
          '<div class="novalid">' . $this->t('Invalid mail.')
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.email-result_message',
          NULL
        )
      );
    }

    return $response;
  }

  /**
   * Our custom ajax response.
   */
  public function setMessage(array &$form, FormStateInterface $form_state, $id = NULL) {
    $response = new AjaxResponse();
    $cat_name = strlen($form_state->getValue('name'));
    $cat_photo = ($form_state->getValue('fid'));
    $fid = json_decode(json_encode($cat_photo), TRUE);
    foreach ($fid as $key) {
      $key = $key['fid'];
    }
    $key['0'] = $fid;
    if (!preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,4}$/', $form_state->getValue('email_user'))) {
      $response->addCommand(
        new HtmlCommand(
          '.email-result_message',
          '<div class="novalid">' . $this->t('Invalid mail.')
        )
      );
    }
    elseif ($cat_name < 2) {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="novalid">' . $this->t('Cat name is too short. Please enter a full cat name.')
        )
      );
    }
    elseif (32 < $cat_name) {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="novalid">' . $this->t('Cat name is too long. Please enter a really cat name.')
        )
      );
    }
    elseif (!isset($key['0'])) {
      $response->addCommand(
        new HtmlCommand(
          '.fid-result_message',
          '<div class="novalid">' . $this->t('Download image field is required')
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="valid">' . $this->t('Your cat name is:&nbsp;') . $form_state->getValue('name')
        )
      );

      $image = $form_state->getValue('fid');
      $data = [
        'name' => $form_state->getValue('name'),
        'email_user' => $form_state->getValue('email_user'),
        'fid' => $image[0],
      ];

      global $_global_fid;
      $image_fid = $_global_fid;
      if ($image[0] != $image_fid) {
        $querys = \Drupal::database();
        $querys->update('file_managed')
          ->condition('fid', $image_fid)
          ->fields(['status' => '0'])
          ->execute();
      }

      $file = File::load($image[0]);
      $file->setPermanent();
      $file->save();

      if (isset($this->id)) {
        \Drupal::database()->update('ar')->fields($data)->condition('id', $this->id)->execute();
      }
      else {
        \Drupal::database()->insert('ar')->fields($data)->execute();
      }

    }

    \Drupal::messenger()->addStatus('Successfully update');

    $response->addCommand(new RedirectCommand('cats'));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
