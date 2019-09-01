{*<!--
/* ********************************************************************************
* The content of this file is subject to the Global Search ("License");
* You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is VTExperts.com
* Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
* All Rights Reserved.
* ****************************************************************************** */
-->*}
<form action="index.php" method="post" id="Settings" class="form-horizontal">
    <input type="hidden" name="module" value="Team">
    <input type="hidden" name="action" value="SaveAjax">
    <input type="hidden" name="selected_module" value="{$SOURCE_MODULE}">
    <input type="hidden" name="fieldid" value="{$FIELD_DATA['fieldid']}">
    <input type="hidden" name="fieldcustomid" value="{$FIELD_CUSTOM_DATA['fieldid']}">
    <input type="hidden" name="fieldteammemberid" value="{$FIELD_TEAMMEMBERS_DATA['fieldid']}">
    {assign var=ALL_ACTIVEGROUP_LIST value=$USER_MODEL->getAccessibleGroups()}
    {assign var=ACCESSIBLE_GROUP_LIST value=$USER_MODEL->getAccessibleGroupForModule($SOURCE_MODULE)}
    <table class="table table-bordered blockContainer showInlineTable equalSplit detailview-table" style="width: 500px;">
        <tr>
            <td class="fieldLabel medium">
                <label>{vtranslate('LBL_LABEL', 'Team')}</label>
            </td>
            <td class="fieldValue medium">
                <input type="text" value="{if $FIELD_DATA['fieldlabel'] neq ''}{$FIELD_DATA['fieldlabel']}{else}{vtranslate('Team', 'Team')}{/if}" name="fieldlabel" id="fieldlabel" />
            </td>
        </tr>
        <tr>
            <td class="fieldLabel medium">
                <label>{vtranslate('LBL_PRIMARY_FIELD', 'Team')}</label>
            </td>
            <td class="fieldValue medium">
                {assign var="DEFAULT_GROUP" value=explode(' |##| ',$FIELD_DATA['defaultvalue'])}
                <div class="row-fluid">
                    <div class="select-search" >
                        <select class="chzn-select" multiple name="defaultGroup[]" id="defaultGroup">
                            <option value="">{vtranslate('LBL_SELECT_OPTION','Vtiger')}</option>
                            {foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEGROUP_LIST}
                                <option value="{$OWNER_ID}" data-picklistvalue= '{$OWNER_NAME}' {if in_array($OWNER_ID,$DEFAULT_GROUP)} selected {/if}
                                        {if array_key_exists($OWNER_ID, $ACCESSIBLE_GROUP_LIST)} data-recordaccess=true {else} data-recordaccess=false {/if} >
                                    {$OWNER_NAME}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td class="fieldLabel medium">
                <label>{vtranslate('LBL_CUSTOM_FIELD', 'Team')}</label>
            </td>
            <td class="fieldValue medium">
                <input type="text" {if $FIELD_TYPE neq 2 } disabled="disabled" {/if} value="{if $FIELD_CUSTOM_DATA['fieldlabel'] neq ''}{$FIELD_CUSTOM_DATA['fieldlabel']}{else}{vtranslate('LBL_FOLLOWERS', 'Team')}{/if}" name="fieldcustom" id="fieldcustom" />
            </td>
        </tr>

        <tr>
            <td class="fieldLabel medium">
                <label>{vtranslate('LBL_TEAM_MEMBERS', 'Team')}</label>
            </td>
            <td class="fieldValue medium">
                <input type="text" name="teammmebers" id="teammembers" value="{if $FIELD_TEAMMEMBERS_DATA['fieldlabel'] neq ''}{$FIELD_TEAMMEMBERS_DATA['fieldlabel']}{else}{vtranslate('LBL_TEAM_MEMBERS', 'Team')}{/if}"/>
                <input type="checkbox" value="1" {if $FIELD_TEAMMEMBERS_DATA['presence'] == 2} checked="checked" {/if} name="teammmebers_status" />
            </td>
        </tr>

        <tr>
            <td class="fieldLabel medium">
                <label>{vtranslate('LBL_DEFAULT_GROUP', 'Team')}</label>
            </td>
            <td class="fieldValue medium">
                {assign var="DEFAULT_GROUP" value=explode(' |##| ',$FIELD_DATA['defaultvalue'])}
                <div class="row-fluid">
                    <div class="select-search" >
                        <select class="chzn-select" multiple name="defaultGroup[]" id="defaultGroup">
                            <option value="">{vtranslate('LBL_SELECT_OPTION','Vtiger')}</option>
                            {foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEGROUP_LIST}
                                <option value="{$OWNER_ID}" data-picklistvalue= '{$OWNER_NAME}' {if in_array($OWNER_ID,$DEFAULT_GROUP)} selected {/if}
                                        {if array_key_exists($OWNER_ID, $ACCESSIBLE_GROUP_LIST)} data-recordaccess=true {else} data-recordaccess=false {/if} >
                                    {$OWNER_NAME}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td class="fieldLabel medium">
                <label>{vtranslate('LBL_ACTIVE', 'Team')}</label>
            </td>

            <td class="fieldValue medium">
                <input type="checkbox" value="1" name="active" {if $FIELD_DATA['presence'] neq 1} checked {/if}/>
            </td>
        </tr>
    </table>
    <br />
    <div class="row-fluid">
        <button class="btn btn-success btnSaveSettings" type="button">{vtranslate('LBL_SAVE', 'Team')}</button>
    </div>
</form>