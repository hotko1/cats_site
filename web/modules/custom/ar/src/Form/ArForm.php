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
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        \Drupal::messenger()->addMessage($this->t("Your cat's name is: " . $form_state->getValue('rival_1')));
    }
}
