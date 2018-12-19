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

  static $off = FALSE;

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
   * Record a translation event in this extension's l10nx_ts_captures table
   *
   * @param $original_string   string untranslated string
   * @param $translated_string string translated string
   * @param $locale            string locale used
   * @param $domain            string domain used
   * @param $context           string context used
   */
  public static function recordTranslationEvent($original_string, $translated_string, $locale, $domain = 'civicrm', $context = '') {
    if ($domain === NULL) {
      $domain = 'civicrm';
    }

    if ($context === NULL) {
      $context = '';
    }

    CRM_Core_DAO::executeQuery("INSERT INTO `l10nx_ts_captures` (`timestamp`, `locale`, `context`, `domain`, `original_hash`, `original`, `translation`)
                                      VALUES (NOW(), %1, %2, %5, SHA1(%3), %3, %4);", [
                                          1 => [$locale, 'String'],
                                          2 => [$context, 'String'],
                                          3 => [$original_string, 'String'],
                                          5 => [$domain, 'String'],
                                          4 => [$translated_string, 'String']]);
  }


  /**
   * Log the given translation
   *
   * @param \Civi\Core\Event\GenericHookEvent $ts_event the translation event
   *
   * @throws \API_Exception
   */
  public function processTranslation(\Civi\Core\Event\GenericHookEvent $ts_event) {
    //if (self::$off) return;
    //self::$off = TRUE;

    // TODO: check the filters, etc.

    self::recordTranslationEvent(
        $ts_event->original_text,
        $ts_event->translated_text,
        $ts_event->locale,
        CRM_Utils_Array::value('domain',  $ts_event->params, 'civicrm'),
        CRM_Utils_Array::value('context', $ts_event->params, ''));

  }

}