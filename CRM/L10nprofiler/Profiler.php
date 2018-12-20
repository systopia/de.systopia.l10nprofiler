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

use CRM_L10nprofiler_ExtensionUtil as E;

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
   * Record a translation event in this extension's l10nx_ts_captures table
   *
   * @param $original_string   string untranslated string
   * @param $translated_string string translated string
   * @param $locale            string locale used
   * @param $domain            string domain used
   * @param $context           string context used
   */
  public static function recordTranslationEvent($original_string, $translated_string, $locale, $domain = 'civicrm', $context = '') {
    if (empty($domain)) {
      $domain = 'civicrm';
    }

    if (empty($context)) {
      $context = 'None';
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
    // TODO: evaluate the filters, etc.

    self::recordTranslationEvent(
        $ts_event->original_text,
        $ts_event->translated_text,
        $ts_event->locale,
        CRM_Utils_Array::value('domain',  $ts_event->params, 'civicrm'),
        CRM_Utils_Array::value('context', $ts_event->params, ''));

  }

  /**
   * Calculate the stats of the currently captured data
   *
   * @return array various statistics
   */
  public static function calculateStats() {
    $query = CRM_Core_DAO::executeQuery("
      SELECT
        COUNT(*)                                      AS total_count,
        COUNT(DISTINCT(original))                     AS original_count,
        COUNT(DISTINCT(domain))                       AS domain_count,
        GROUP_CONCAT(DISTINCT domain SEPARATOR ', ')  AS domain_list,
        COUNT(DISTINCT(locale))                       AS locale_count,
        GROUP_CONCAT(DISTINCT locale SEPARATOR ', ')  AS locale_list,
        COUNT(DISTINCT(context))                      AS context_count,
        GROUP_CONCAT(DISTINCT context SEPARATOR ', ') AS context_list,
        MIN(timestamp)                                AS timestamp_start,
        MAX(timestamp)                                AS timestamp_end
      FROM l10nx_ts_captures;");
    $query->fetch();

    return [
        'total_count'     => [E::ts("Total Translations"), $query->total_count],
        'original_count'  => [E::ts("Different Strings"), $query->original_count],
        'domain_list'     => [E::ts("Domains"), $query->domain_list],
        'locale_list'     => [E::ts("Locales"), $query->locale_list],
        'context_list'    => [E::ts("Contexts"), $query->context_list],
        'timestamp_start' => [E::ts("First Event"), $query->timestamp_start ? date("H:i:s (Y-m-d)", strtotime($query->timestamp_start)) : ''],
        'timestamp_end'   => [E::ts("First Event"), $query->timestamp_end ? date("H:i:s (Y-m-d)", strtotime($query->timestamp_end)) : ''],
    ];
  }
}