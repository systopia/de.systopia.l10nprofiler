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
 * Collection of upgrade steps.
 */
class CRM_L10nprofiler_Upgrader extends CRM_L10nprofiler_Upgrader_Base {

  /**
   * Create data structure
   */
  public function install() {
    $this->executeSqlFile('sql/profiler_data.sql');
  }

}
