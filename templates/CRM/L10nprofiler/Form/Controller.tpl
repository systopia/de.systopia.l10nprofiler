{*-------------------------------------------------------+
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
+-------------------------------------------------------*}


<div class="crm-section">
  <div class="label">{$form.enabled.label}</div>
  <div class="content">{$form.enabled.html}</div>
  <div class="clear"></div>
</div>

<div class="crm-section">
  <div class="label">{$form.exclude_domains.label}</div>
  <div class="content">{$form.exclude_domains.html}</div>
  <div class="clear"></div>
</div>

<div class="crm-section">
  <div class="label">{$form.locales.label}</div>
  <div class="content">{$form.locales.html}</div>
  <div class="clear"></div>
</div>

<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
