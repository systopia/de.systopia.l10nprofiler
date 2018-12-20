<?php
/*-------------------------------------------------------+
| L10n Profiling Extension                               |
| Copyright (C) 2018 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_L10nprofiler_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_L10nprofiler_Form_Controller extends CRM_Core_Form {

  /**
   * @var string stores requested action, if any
   */
  protected $l10n_action = NULL;


  public function buildQuickForm() {
    CRM_Utils_System::setTitle(E::ts("Translation Event Captures"));
    $this->executeAction();

    // add form elements
    $this->add(
        'text',
        'exclude_domains',
        E::ts('Exclude Domains'),
        ['class' => 'huge'],
        FALSE

    );

    $this->add(
      'select',
      'locales',
      E::ts('Restrict to Locale(s)'),
      CRM_Core_I18n::languages(FALSE),
      FALSE,
      ['class' => 'crm-select2', 'multiple' => 'multiple']
    );

    $this->addButtons(array(
      [
          'type'      => 'submit',
          'name'      => E::ts('Update'),
          'isDefault' => TRUE,
      ],
    ));

    // form workflow
    $this->add('hidden', 'l10n_action');
    $this->add('hidden', 'enabled');

    // set current values as default
    $current_values = CRM_L10nprofiler_Configuration::getConfiguration();
    $this->setDefaults($current_values);
    $this->assign('l10n_enabled', CRM_Utils_Array::value('enabled', $current_values, 0));

    // calculate stats
    $stats = CRM_L10nprofiler_Profiler::calculateStats();
    $this->assign('stats', $stats);

    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    CRM_Core_Error::debug_log_message("Action: " . $values['l10n_action']);

    $values['enabled'] = CRM_L10nprofiler_Configuration::getSetting('enabled');
    CRM_L10nprofiler_Configuration::setConfiguration($values);
    parent::postProcess();
  }

  /**
   * Execute any action submitted
   */
  protected function executeAction() {
    $this->l10n_action = CRM_Utils_Request::retrieve('l10n_action', 'String');
    switch ($this->l10n_action) {
      case 'l10n_refresh':
        // nothing to do
        break;

      case 'l10n_clear':
        // clear capture DB
        CRM_Core_DAO::executeQuery("TRUNCATE l10nx_ts_captures;");
        break;

      case 'l10n_enable':
        // clear capture DB
        CRM_L10nprofiler_Configuration::setSetting('enabled', 1);
        break;

      case 'l10n_disable':
        // clear capture DB
        CRM_L10nprofiler_Configuration::setSetting('enabled', 0);
        break;


      default:
        CRM_Core_Session::setStatus(E::ts("Unknown action '%1'", [1 => $this->l10n_action]));
        break;
    }
  }
}
