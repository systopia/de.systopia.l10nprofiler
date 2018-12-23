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
        'text',
        'restrict_domains',
        E::ts('Only Domains'),
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

      case 'l10n_download_pot':
        self::createPO(TRUE);
        break;

      case 'l10n_download_po':
        self::createPO(FALSE);
        break;

      case '':
      case NULL:
        // no action
        break;

      default:
        CRM_Core_Session::setStatus(E::ts("Unknown action '%1'", [1 => $this->l10n_action]));
        break;
    }
  }

  /**
   * Generate a PO or POT file and make browser download it
   *
   * @param $as_template bool should a template (POT) file be generated?
   */
  public static function createPO($as_template) {
    $po_data  = "# File generated by de.systopia.l10nprofiler extension.\n";
    $po_data .= "# See https://github.com/systopia/de.systopia.l10nprofiler\n";
    $po_data .= "msgid \"\"\n";
    $po_data .= "msgstr \"\"\n";
    $po_data .= "\"Project-Id-Version: FIXME\\n\"\n";
    $po_data .= "\"MIME-Version: 1.0\\n\"\n";
    $po_data .= "\"Content-Type: text/plain; charset=UTF-8\\n\"\n";
    $po_data .= "\"Content-Transfer-Encoding: 8bit\\n\"\n";

    if ($as_template) {
      $po_data .= "\"POT-Creation-Date: " . date('Y-m-d H:iT00') . "\\n\"\n";
    } else {
      $po_data .= "\"PO-Creation-Date: " . date('Y-m-d H:iT00') . "\\n\"\n";
      $po_data .= "\"Last-Translator: FIXME <email@fix.me>\\n\"\n";
      $po_data .= "\"Language-Team: FIXME <email@fix.me>\\n\"\n";

      // add language
      $locale = CRM_Core_DAO::singleValueQuery("SELECT locale FROM l10nx_ts_captures LIMIT 1");
      if ($locale) {
        $po_data .= "\"Language: {$locale}\\n\"\n";
      }
    }

    $ts_entry = CRM_Core_DAO::executeQuery("
      SELECT original, translation, context
      FROM l10nx_ts_captures
      GROUP BY original, context");
    while ($ts_entry->fetch()) {
      $po_data  .= "\n";

      // TODO: insert source

      // insert context
      if (!empty($ts_entry->context) && $ts_entry->context != 'None') {
        $po_data  .= "msgctxt \"{$ts_entry->context}\"\n";
      }

      // add original (key)
      $original_encoded = self::po_encode_string($ts_entry->original);
      $po_data  .= "msgid \"{$original_encoded}\"\n";
      if ($as_template) {
        $po_data  .= "msgstr \"\"\n";
      } else {
        $translation_encoded = self::po_encode_string($ts_entry->translation);
        $po_data  .= "msgstr \"{$translation_encoded}\"\n";
      }
    }

    // finally: dump on the user
    $file_name = $as_template ? 'captured_translation.pot' : 'captured_translation.po';
    CRM_Utils_System::download($file_name, 'text/x-gettext-translation', $po_data);
  }

  /**
   * Encode string to be used in PO/POT files
   * @param $string string string to be translationn
   * @return string
   */
  protected static function po_encode_string($string) {
    return str_replace('"', '\"', $string);
  }
}
