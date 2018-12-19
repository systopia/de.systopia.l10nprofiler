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

  public function buildQuickForm() {
    // add form elements
    $this->add(
        'checkbox',
        'enabled',
        E::ts("Capturing Enabled?")
    );

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

    // set current values as default
    $current_values = CRM_Core_BAO_Setting::getItem(E::LONG_NAME, 'l10n_profiler_settings');
    if ($current_values === NULL) {
      // new here? Set some defaults
      $current_values = [
          'exclude_domains' => E::LONG_NAME,
          'locales'         => [CRM_Core_I18n::getLocale()],
      ];
    }
    $this->setDefaults($current_values);

    // calculate stats
    $stats = self::calculateStats();
    $this->assign('stats', $stats);

    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    CRM_Core_BAO_Setting::setItem($values, E::LONG_NAME, 'l10n_profiler_settings');
    parent::postProcess();
  }

  /**
   * Calculate the stats of the currently captured data
   *
   * @return array various statistics
   */
  public static function calculateStats() {
    $query = CRM_Core_DAO::executeQuery("
      SELECT
        COUNT(*)                  AS total_count,
        COUNT(DISTINCT(original)) AS original_count,
        COUNT(DISTINCT(domain))   AS domain_count,
        COUNT(DISTINCT(locale))   AS locale_count,
        COUNT(DISTINCT(context))  AS context_count
      FROM l10nx_ts_captures;");
    $query->fetch();

    return [];
  }
}
