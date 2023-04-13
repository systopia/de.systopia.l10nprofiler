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

require_once 'l10nprofiler.civix.php';
use CRM_L10nprofiler_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function l10nprofiler_civicrm_config(&$config) {
  _l10nprofiler_civix_civicrm_config($config);

  // subscribe to the events
  CRM_L10nprofiler_Configuration::subscribeToEvents();
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function l10nprofiler_civicrm_install() {
  _l10nprofiler_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function l10nprofiler_civicrm_enable() {
  _l10nprofiler_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function l10nprofiler_civicrm_navigationMenu(&$menu) {
  _l10nprofiler_civix_insert_navigation_menu($menu, 'Administer/Localization', array(
      'label'      => E::ts('Translation Profiling'),
      'name'       => 'l10n_profiler',
      'url'        => 'civicrm/l10nx/profiler',
      'permission' => 'administer CiviCRM',
      'operator'   => 'OR',
      'separator'  => 0,
  ));
  _l10nprofiler_civix_navigationMenu($menu);
}
