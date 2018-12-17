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

use Civi\API\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CRM_L10nprofiler_Profiler implements EventSubscriberInterface {
  /**
   * @return array
   */
  public static function getSubscribedEvents() {
    return array(
        'civi.l10n.dts_post' => array(
            array('processTranslation', Events::W_LATE),
        ),
        'civi.l10n.ts_post' => array(
            array('processTranslation', Events::W_LATE),
        ),
    );
  }

  /**
   * Record a translation event in this extension's civicrm_l10nx_tsprofile table
   * @param $data array
   */
  public static function recordTranslationEvent($original_string, $translated_string, $locale, $context) {

  }

  /**
   * Log the given translation
   *
   * @param \Civi\Core\Event\GenericHookEvent $event the translation event
   *
   * @throws \API_Exception
   */
  public function processTranslation(\Civi\Core\Event\GenericHookEvent $event) {

    CRM_Core_Error::debug_log_message("BANG!");
//    $apiRequest = $event->getApiRequest();
//    if ($apiRequest['version'] > 3) {
//      return;
//    }
//
//    $apiRequest['fields'] = _civicrm_api3_api_getfields($apiRequest);
//
//    _civicrm_api3_swap_out_aliases($apiRequest, $apiRequest['fields']);
//    if (strtolower($apiRequest['action']) != 'getfields') {
//      if (empty($apiRequest['params']['id'])) {
//        $apiRequest['params'] = array_merge($this->getDefaults($apiRequest['fields']), $apiRequest['params']);
//      }
//      // Note: If 'id' is set then verify_mandatory will only check 'version'.
//      civicrm_api3_verify_mandatory($apiRequest['params'], NULL, $this->getRequired($apiRequest['fields']));
//    }
//
//    $event->setApiRequest($apiRequest);
  }

}