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

class CRM_L10nprofiler_Configuration {

  /**
   * @var array configuration data
   */
  protected static $_configuration = NULL;

  /**
   * get the whole configuration blob
   *
   * @return array
   */
  public static function getConfiguration() {
    if (self::$_configuration === NULL) {
      self::$_configuration = CRM_Core_BAO_Setting::getItem(E::LONG_NAME, 'l10n_profiler_settings');
      if (self::$_configuration === NULL) {
        // new here? Set some sensible defaults
        self::$_configuration = [
            'enabled'         => 0,
            'exclude_domains' => E::LONG_NAME,
            'locales'         => [CRM_Core_I18n::getLocale()],
        ];
      }
    }
    return self::$_configuration;
  }

  /**
   * Set the whole configuration blob
   *
   * @param $configuration array the whole configuration
   */
  public static function setConfiguration($configuration) {
    self::$_configuration = $configuration;
    self::storeConfiguration();
  }

  /**
   * Set the whole configuration blob
   *
   * @param $configuration array the whole configuration
   */
  public static function storeConfiguration() {
    CRM_Core_BAO_Setting::setItem(self::$_configuration, E::LONG_NAME, 'l10n_profiler_settings');
  }

  /**
   * Get a single setting's value
   *
   * @param $name    string setting name/key
   * @param $default mixed  default, if setting is not defined
   * @return mixed setting's value
   */
  public static function getSetting($name, $default = NULL) {
    $settings = self::getConfiguration();
    return CRM_Utils_Array::value($name, $settings, $default);
  }

  /**
   * Get a single setting's value
   *
   * @param $name    string setting name/key
   * @param $value   mixed  new value
   * @return mixed setting's value
   */
  public static function setSetting($name, $value) {
    $settings = self::getConfiguration();
    $settings[$name] = $value;
    self::setConfiguration($settings);
  }

  /**
   * Check if profiling is currently enabled
   *
   * @return bool enabled
   */
  public static function profilingEnabled() {
    return (bool) self::getSetting('enabled', FALSE);
  }



  /**
   * This function subscribes to l10nx's ts_ost and dts_post events
   *  if the profiling is enabled.
   */
  public static function subscribeToEvents() {
    if (CRM_L10nprofiler_Configuration::profilingEnabled()) {
      \Civi::dispatcher()->addSubscriber(new CRM_L10nprofiler_Profiler());
    }
  }

  /**
   * This hook allows another extension to provide it's own .mo file
   *   for the given domain
   *
   * If no domain is given, this will be passed on to CiviCRM's internal ts system
   *
   * @param string|null $mo_file_path
   *   The path for the new mo file
   * @param string $locale
   *   locale for translating.
   * @param string $context
   *   The translation context, either 'ts' or 'dts'
   * @param string $domain
   *   The translation domain
   */
  public static function custom_mo(&$mo_file_path, $locale, $context, $domain) {
    \Civi::dispatcher()->dispatch('civi.l10n.custom_mo', \Civi\Core\Event\GenericHookEvent::create([
        'mo_file_path' => $mo_file_path,
        'locale'       => $locale,
        'context'      => &$context,
        'domain'       => $domain
    ]));
  }


  /**
   * This hook allows other extension to plug into the dts function directly
   *
   * Remark: we intentionally created _two_ events to enable people to efficiently
   *   subscribe to only one of the two.
   *
   * @param string $locale
   *   locale for translating.
   * @param string $original_text
   *   The text to be translated
   * @param string $translated_text
   *   The text with the current translation
   * @param array $params
   *   The parameters passed to dts
   */
  public static function dts_post($locale, $original_text, &$translated_text, $params) {
    \Civi::dispatcher()->dispatch('civi.l10n.dts_post', \Civi\Core\Event\GenericHookEvent::create([
        'locale'          => $locale,
        'original_text'   => $original_text,
        'translated_text' => &$translated_text,
        'params'          => $params
    ]));
  }

  /**
   * This hook allows other extension to plug into the ts function directly
   *
   * @param string $locale
   *   locale for translating.
   * @param string $original_text
   *   The text to be translated
   * @param string $translated_text
   *   The text with the current translation
   * @param array $params
   *   The parameters passed to ts
   */
  public static function ts_post($locale, $original_text, &$translated_text, $params) {
    \Civi::dispatcher()->dispatch('civi.l10n.ts_post', \Civi\Core\Event\GenericHookEvent::create([
        'locale'          => $locale,
        'original_text'   => $original_text,
        'translated_text' => &$translated_text,
        'params'          => $params
    ]));
    //CRM_Core_Error::debug_log_message("'{$original_text}' translated as '{$translated_text}' ({$locale})");
  }

}