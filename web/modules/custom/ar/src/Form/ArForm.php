<?php

namespace Drupal\ar\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Our custom ar form
 */
class ArForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
    public function getFormId()
    {
        return "ar_arform";
    }

  /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['name']=[
        '#type' => 'textfield',
        '#title' => $this->t('Your cat’s name:'),
        '#required' => true,
        '#attributes' => array(
            'placeholder' => $this->t('The length of the name is 2-32 letters.'),
        ),
        ];

        $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add cat'),
        ];

        return $form;
    }

  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if (strlen($form_state->getValue('name')) < 2) {
            $form_state->setErrorByName('name', $this->t('Cat name is too short. Please enter a full cat name.'));
        }
        if (strlen($form_state->getValue('name')) > 32) {
            $form_state->setErrorByName('name', $this->t('Cat name is too long. Please enter a really cat name.'));
        }
    }

  /**
   * {@inheritdoc}
   */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        \Drupal::messenger()->addMessage($this->t("Your cat name is: " . $form_state->getValue('name')));
    }
}
