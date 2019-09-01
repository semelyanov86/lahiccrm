{*<!--
/* ********************************************************************************
* The content of this file is subject to the Global Search ("License");
* You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is VTExperts.com
* Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
* All Rights Reserved.
* ****************************************************************************** */
-->*}
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3>{vtranslate('Team', 'Team')}</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="summaryWidgetContainer" id="team_settings">
        <div class="tab-pane active" id="fields">
            <div class="row-fluid">
                <div class="select-search" style="margin-left:100px;margin-top:15px;">
                    <select class="chzn-select select2" id="available_module" name="available_module" style="width: 300px;">
                        <option value="">{vtranslate('LBL_SELECT_MODULE', 'Team')}</option>
                        {foreach from=$ALL_MODULE item=MODULE}
                            <option value="{$MODULE}">{vtranslate($MODULE, $MODULE)}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <br/>
            <div id="customField">

            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="widget_header row-fluid">
            <h4>{vtranslate('LBL_SCHEDULER_SETUP', 'Team')}</h4>
        </div>
        <div class="clearfix"></div>
        <div class="summaryWidgetContainer" id="cron_settings">
            <div class="tab-pane active" id="fields">
                <div class="row-fluid">
                    <div class="select-search" style="margin-left:100px;">
                        <label>{vtranslate('LBL_CRONJOB', 'Team')}</label>
                        <input type="checkbox" style="margin-left: 10px;" name="enable" value="1" id="enable" {if $IS_ENABLE eq '1'}checked="checked"{/if} />
                    </div>
                </div>
                <div class="row-fluid" style="margin-top:15px;">
                    <span>{vtranslate('LBL_CRONJOB_MESSAGE', 'Team')}</span>
                </div>
            </div>
        </div>
    </div>
</div>