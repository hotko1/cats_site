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

        $form['rival_1']=[
        '#type' => 'textfield',
        '#title' => $this->t('Your cat’s name:'),
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
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        \Drupal::messenger()->addMessage($this->t("Your cat's name is: " . $form_state->getValue('rival_1')));
    }
}
