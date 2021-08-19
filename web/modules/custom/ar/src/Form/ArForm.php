<?php

namespace Drupal\ar\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\file\Entity\File;

/**
 * Our custom ajax form.
 */
class ArForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "ar_form";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>',
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#required' => TRUE,
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
      '#attributes' => [
        'placeholder' => $this->t('Only Latin letters, "_" and "-".'),
      ],
      '#ajax' => [
        'callback' => '::mailValidateCallback',
        'event' => 'keyup',
      ],
    ];

    $form['fid'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Download image'),
      '#required' => TRUE,
      '#description' => $this->t('Image should be less than 2 MB and in JPEG, JPG or PNG format.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2097152],
      ],
      '#upload_location' => 'public://images',
    ];

    $form['submit'] = [
      '#type' => 'button',
      '#value' => $this->t('Add cat'),
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
  public function setMessage(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $cat_name = strlen($form_state->getValue('name'));
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
    else {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="valid">' . $this->t('Your cat name is:&nbsp;') . $form_state->getValue('name')
        )
      );

      $image = $form_state->getValue('fid');
      $time = \Drupal::time()->getCurrentTime();
      $data = [
        'id' => $form_state->getValue('id'),
        'name' => $form_state->getValue('name'),
        'email_user' => $form_state->getValue('email_user'),
        'fid' => $image[0],
        'time' => $time,
      ];

      $file = File::load($image[0]);
      $file->setPermanent();
      $file->save();

      \Drupal::database()->insert('ar')->fields($data)->execute();

    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
