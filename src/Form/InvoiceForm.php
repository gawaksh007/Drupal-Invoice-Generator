<?php

namespace Drupal\invoice\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface; 
use Drupal\Core\Url;
use Drupal\Core\Link;



class InvoiceForm extends ConfigFormBase 

  {
 
  public function getFormId() {
    return 'rsvplist_email_form';
  }


 protected function getEditableConfigNames() {
   return [
     'invoice.config',
   ];
 }

  
  public function buildForm(array $form, FormStateInterface $form_state) 

  {


    //Form to input the general settings.

   // Get template names
   // $templates = _invoice_get_templates();

  $form['general'] = array(
    '#type' => 'fieldset',
    '#title' => t('General settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['general']['locale'] = array(
    '#type' => 'textfield',
    '#title' => t('Locale'),
    '#required' => TRUE,
    '#default_value' => \Drupal::state()->get('invoice_locale', ''),
    '#description' => t('Category/locale names can be found in !rfc1766 and !iso639. Systems can have different naming schemes for locales.',
      array(
        

        $url = Url::fromUri('http://www.faqs.org/rfcs/rfc1766.htm'),
        $external_link => \Drupal::l(t('RFC 1786'), $url),
        '!rfc1766' => $external_link,

        $url = Url::fromUri('http://www.w3.org/WAI/ER/IG/ert/iso639.htm'),
        $external_link = \Drupal::l(t('ISO 639'),$url),


        '!iso639' => $external_link,
      )
    ) 
  );
  $form['general']['date_format'] = array(
    '#type' => 'textfield',
    '#title' => t('Date format'),
    '#required' => TRUE,
    '#default_value' =>  \Drupal::state()->get('invoice_date_format', ''),
    '#size' => 20,
  );
  $form['general']['vat'] = array(
    '#type' => 'textfield',
    '#title' => t('Default VAT precentage'),
    '#required' => TRUE,
    '#default_value' =>  \Drupal::state()->get('invoice_vat', ''),
    '#size' => 3,
  );
  $form['general']['pay_limit'] = array(
    '#type' => 'textfield',
    '#title' => t('Pay limit'),
    '#required' => TRUE,
    '#default_value' =>  \Drupal::state()->get('invoice_pay_limit', ''),
    '#description' => t('Pay limit in days'),
    '#size' => 3,
  );
  $form['general']['invoice_number_zerofill'] = array(
    '#type' => 'textfield',
    '#title' => t('Invoice number zerofill'),
    '#required' => FALSE,
    '#default_value' =>  \Drupal::state()->get('invoice_invoice_number_zerofill', ''),
    '#description' => t('If you want an invoice number to be displayed as "0001" fill in 4. If you just want to display invoice number "1" leave/set empty.'),
    '#size' => 3,
  );
  $form['general']['invoice_number_prefix'] = array(
    '#type' => 'textfield',
    '#title' => t('Invoice number prefix'),
    '#required' => FALSE,
    '#default_value' =>  \Drupal::state()->get('invoice_invoice_number_prefix', ''),
    '#size' => 20,
  );



  /*-------------------------------------------------------------------------------------------------*/

  //Form to choose invoice columns. 

   $form['general']['display_column'] = array(
    '#type' => 'fieldset',
    '#title' => t('Display invoice columns'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general']['display_column']['display_column_vat'] = array(
    '#type' => 'checkbox',
    '#title' => t('VAT'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_display_column_vat', ''),
  );
  $form['general']['display_column']['display_column_exunitcost'] = array(
    '#type' => 'checkbox',
    '#title' => t('Unitcost (ex)'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_display_column_exunitcost', ''),
  );
  $form['general']['display_column']['display_column_incunitcost'] = array(
    '#type' => 'checkbox',
    '#title' => t('Unitcost (inc)'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_display_column_incunitcost', ''),
  );
  $form['general']['display_column']['display_column_extotal'] = array(
    '#type' => 'checkbox',
    '#title' => t('Total (ex)'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_display_column_extotal', ''),
  );
  $form['general']['display_column']['display_column_inctotal'] = array(
    '#type' => 'checkbox',
    '#title' => t('Total (inc)'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_display_column_inctotal', ''),
  );

/*--------------------------------------------------------------------------------------------------*/
  //Form to input the supplier details.
  


  $form['general']['supplier'] = array(
    '#type' => 'fieldset',
    '#title' => t('Supplier details'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general']['supplier']['supplier_company_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Company name'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_company_name', ''),
  );
  $form['general']['supplier']['supplier_street'] = array(
    '#type' => 'textfield',
    '#title' => t('Street'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_street', ''),
  );
  $form['general']['supplier']['supplier_building_number'] = array(
    '#type' => 'textfield',
    '#title' => t('Building number'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_building_number', ''),
  );
  $form['general']['supplier']['supplier_zipcode'] = array(
    '#type' => 'textfield',
    '#title' => t('Zipcode'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_zipcode', ''),
  );
  $form['general']['supplier']['supplier_city'] = array(
    '#type' => 'textfield',
    '#title' => t('City'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_city', ''),
  );
  $form['general']['supplier']['supplier_state'] = array(
    '#type' => 'textfield',
    '#title' => t('State'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_state', ''),
  );
  $form['general']['supplier']['supplier_country'] = array(
    '#type' => 'textfield',
    '#title' => t('Country'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_country', ''),
  );
  $form['general']['supplier']['supplier_phone'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_phone', ''),
  );
  $form['general']['supplier']['supplier_fax'] = array(
    '#type' => 'textfield',
    '#title' => t('Fax'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_fax', ''),
  );
  $form['general']['supplier']['supplier_email'] = array(
    '#type' => 'textfield',
    '#title' => t('Email'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_email', ''),
  );
  $form['general']['supplier']['supplier_web'] = array(
    '#type' => 'textfield',
    '#title' => t('Web address'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_web', ''),
  );
  $form['general']['supplier']['supplier_coc_number'] = array(
    '#type' => 'textfield',
    '#title' => t('CoC Number'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_coc_number', ''),
  );
  $form['general']['supplier']['supplier_vat_number'] = array(
    '#type' => 'textfield',
    '#title' => t('VAT Number'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_supplier_vat_number', ''),
  );


/*---------------------------------------------------------------------------------------------*/
//Form to input the api details

 $form['general']['api'] = array(
    '#type' => 'fieldset',
    '#title' => t('API details'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general']['api']['api_root_username'] = array(
    '#type' => 'textfield',
    '#title' => t('API root username'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_api_root_username', ''),
    '#description' => t('The username defined here has access to perform all invoice API operations. But you can also'
        . ' only set an API username per template or both.')
  );
  $form['general']['api']['api_allowed_ips'] = array(
    '#type' => 'textfield',
    '#title' => t('Allowed ips'),
    '#required' => FALSE,
    '#default_value' => \Drupal::state()->get('invoice_api_allowed_ips', ''),
    '#description' => t('Restrict authentication access to these ips (multiple comma seperated)')
  );

  // Build array for selecting the default template
  $default_templates = array();
  foreach ($templates as $template) {
    $default_templates[$template] = ucfirst($template);
  }

  $form['general']['default_template'] = array(
    '#type' => 'select',
    '#title' => t('Default template'),
    '#options' => $default_templates,
    '#default_value' => \Drupal::state()->get('invoice_default_template', 'default'),
    '#required' => TRUE,
    '#size' => 1,
  );

/*----------------------------------------------------------------------------------------------------*/

//Build form for template values

foreach ($templates as $template) {
    $form[$template] = array(
      '#type' => 'fieldset',
      '#title' => t('Template') . ' (' . $template . ')',
      '#collapsible' => TRUE,
      '#collapsed' => $template == 'default' ? FALSE : TRUE,
      '#description' => t('If fields are also set in invoice general settings and the template field is empty, the general setting of the field will be used.'),
    );
    $form[$template][$template . '_locale'] = array(
      '#type' => 'textfield',
      '#title' => t('Locale'),
      '#required' => FALSE,
      '#default_value' => _invoice_get_variable($template, 'locale', ''),
      '#size' => 20,
    );
    $form[$template][$template . '_date_format'] = array(
      '#type' => 'textfield',
  '#title' => t('Date format'),
      '#required' => FALSE,
      '#default_value' => _invoice_get_variable($template, 'date_format', ''),
      '#description' => t('For example m/d/Y.') . ' ' . t('See !link.',
        array('!link' => l(t('http://www.php.net/date'), 'http://www.php.net/date',
          array('absolute' => TRUE)))) . ' ' .
            t('The date on the invoice will look like: @date_format',
              array('@date_format' => date(_invoice_get_variable($template, 'date_format')))),
      '#size' => 20,
    );
    $form[$template][$template . '_vat'] = array(
      '#type' => 'textfield',
      '#title' => t('Default vat percentage'),
      '#required' => FALSE,
      '#default_value' => _invoice_get_variable($template, 'vat', ''),
      '#size' => 3,
    );
    $form[$template][$template . '_pay_limit'] = array(
      '#type' => 'textfield',
      '#title' => t('Pay limit'),
      '#required' => FALSE,
      '#default_value' => _invoice_get_variable($template, 'pay_limit', ''),
      '#description' => t('Pay limit in days'),
      '#size' => 3,
    );







return $form;




}

}











   




    /*$node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;
    $form['email']= array(
      '#title' => t('Email Address'),
      '#type' => 'textfield',
      '#size' => 25,
      'description' => t("We'll send updates to the email address you provide."),
      '#required' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('RSVP'),
    );
    $form['nid'] = array(
      '#type' => 'hidden',
      '#value' => $nid,
    );
    return $form;
  }

  /**
    * (@inheritdoc)
    */









/*

  public function validateForm(array &$form, FormStateInterface $form_state) 

  {
    $value = $form_state->getValue('email');
    if ($value == !\Drupal::service('email.validator')->isValid($value)) {
      $form_state->setErrorByName ('email', t('The email address %mail is not valid.', array('%mail' => $value)));
      return;
    }
    $node = \Drupal::routeMatch()->getParameter('node');
    // Check if email already is set for this node
    $select = Database::getConnection()->select('rsvplist','r');
    $select->fields('r',array('nid'));
    $select->condition('nid', $node->id());
    $select->condition('mail', $value);
    $results = $select->execute();
    if (!empty($results->fetchCol())) {
      // We found a row with this nid and email.
      $form_state->setErrorByName('email',t('The address %mail is already subscribed to this list.', array('%mail' => $value)));
    }
  }
  /**
    * (@inheritdoc)
    */
  /*public function submitForm(array &$form, FormStateInterface $form_state) {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    db_insert('rsvplist')
      ->fields(array(
        'mail' => $form_state->getValue('email'),
        'nid' => $form_state->getValue('nid'),
        'uid' => $user->id(),
        'created' => time(),
    ))
    ->execute();
  drupal_set_message(t('Thanks for your RSVP, you are on the list for the event.'));
  }
}
