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

{literal}
<style>
  div.l10nx-stats-div {
    margin: 10px;
    border: 2px solid #a1a1a1;
    padding: 0px 10px;
    border-radius:15px;
  }
</style>
{/literal}

{* Stats view *}
<div class="l10nx-stats-div">
<table class="crm-grid-table l10nx-stats">
{foreach from=$stats item=stat}
  <tr>
    <td>{$stat.0}</td>
    <td>{$stat.1}</td>
  </tr>
{/foreach}
</table>
</div>

{* Options *}
<br/>
<h3>{ts domain="de.systopia.l10nprofiler"}Filters{/ts}</h3>
<div class="crm-section">
  <div class="label">{$form.exclude_domains.label}&nbsp;<a onclick='CRM.help("{ts domain="de.systopia.l10nprofiler"}Exclude Domains{/ts}", {literal}{"id":"id-domain-exclude","file":"CRM\/L10nprofiler\/Form\/Controller"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.l10nprofiler"}Help{/ts}" class="helpicon">&nbsp;</a></div>
  <div class="content">#{$form.exclude_domains.html}#</div>
  <div class="clear"></div>
</div>

<div class="crm-section">
  <div class="label">{$form.restrict_domains.label}&nbsp;<a onclick='CRM.help("{ts domain="de.systopia.l10nprofiler"}Restrict to Domains{/ts}", {literal}{"id":"id-domain-restrict","file":"CRM\/L10nprofiler\/Form\/Controller"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.l10nprofiler"}Help{/ts}" class="helpicon">&nbsp;</a></div>
  <div class="content">#{$form.restrict_domains.html}#</div>
  <div class="clear"></div>
</div>

<div class="crm-section">
  <div class="label">{$form.locales.label}&nbsp;<a onclick='CRM.help("{ts domain="de.systopia.l10nprofiler"}Locale{/ts}", {literal}{"id":"id-locale","file":"CRM\/L10nprofiler\/Form\/Controller"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.l10nprofiler"}Help{/ts}" class="helpicon">&nbsp;</a></div>
  <div class="content">{$form.locales.html}</div>
  <div class="clear"></div>
</div>

<br/>
<div class="crm-submit-buttons">
{if $l10n_enabled}
  <span class="crm-button crm-button-type-submit crm-button_qf_Controller_submit crm-i-button">
    <i class="crm-i fa-refresh"></i>
    <input class="crm-form-submit default validate" crm-icon="fa-refresh" name="l10n_refresh" value="{ts domain="de.systopia.l10nprofiler"}Refresh{/ts}" type="submit" onclick="l10nx_processButton(event, this);">
  </span>
  <span class="crm-button crm-button-type-submit crm-button_qf_Controller_submit crm-i-button">
    <i class="crm-i fa-power-off"></i>
    <input class="crm-form-submit default validate" crm-icon="fa-power-off" name="l10n_disable" value="{ts domain="de.systopia.l10nprofiler"}Stop Profiling{/ts}" type="submit" onclick="l10nx_processButton(event, this);">
  </span>
{else}
  <span class="crm-button crm-button-type-submit crm-button_qf_Controller_submit crm-i-button">
    <i class="crm-i fa-signal"></i>
    <input class="crm-form-submit default validate" crm-icon="fa-archive" name="l10n_enable" value="{ts domain="de.systopia.l10nprofiler"}Start Profiling{/ts}" type="submit" onclick="l10nx_processButton(event, this);">
  </span>
  <span class="crm-button crm-button-type-submit crm-button_qf_Controller_submit crm-i-button">
    <i class="crm-i fa-download"></i>
    <input class="crm-form-submit default validate" crm-icon="fa-download" name="l10n_download_pot" value="{ts domain="de.systopia.l10nprofiler"}Create .POT{/ts}" type="submit" onclick="l10nx_processButton(event, this);">
  </span>
  <span class="crm-button crm-button-type-submit crm-button_qf_Controller_submit crm-i-button">
    <i class="crm-i fa-download"></i>
    <input class="crm-form-submit default validate" crm-icon="fa-download" name="l10n_download_po" value="{ts domain="de.systopia.l10nprofiler"}Create .PO{/ts}" type="submit" onclick="l10nx_processButton(event, this);">
  </span>
  <span class="crm-button crm-button-type-submit crm-button_qf_Controller_submit crm-i-button">
    <i class="crm-i fa-eraser"></i>
    <input class="crm-form-submit default validate" crm-icon="fa-eraser" name="l10n_clear" value="{ts domain="de.systopia.l10nprofiler"}Clear{/ts}" type="submit" onclick="l10nx_processButton(event, this);">
  </span>
{/if}

</div>

{$form.l10n_action.html}

{*<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>*}

{literal}
<script type="application/javascript">

  /**
   * Custom button handler
   * @param event
   */
  function l10nx_processButton(event, button) {
    let action = cj(button).attr('name');
    cj("input[name=l10n_action]").val(action);
    // event.preventDefault();
  }

</script>
{/literal}