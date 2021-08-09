<?php

namespace Drupal\ar\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

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

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => $this->t('Only Latin letters, "_" and "-".'),
      ],
    ];

    $form['submit'] = [
      '#type' => 'button',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::setMessage',
      ],
    ];

    return $form;
  }

  /**
   * Our custom ajax response.
   */
  public function setMessage(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $a = strlen($form_state->getValue('name'));
    if ($a < 2) {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="novalid">Cat name is too short. Please enter a full cat name.</div>'
        )
      );
    }
    elseif (32 < $a) {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="novalid">Cat name is too long. Please enter a really cat name.</div>'
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="valid">Your cat name is: ' . $form_state->getValue('name')
        )
      );
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
