{*<!--
/* ********************************************************************************
 * The content of this file is subject to the Team ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}
{strip}
    {assign var="FIELD_INFO" value=Vtiger_Util_Helper::toSafeHTML(Zend_Json::encode($FIELD_MODEL->getFieldInfo()))}
    {assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
    {*{if $VIEW eq 'Edit'}*}
    {assign var=ALL_ACTIVEUSER_LIST value=Vtiger_Multiusers_UIType::getAccessibleUsers()}
    {assign var=ALL_ACTIVEGROUP_LIST value=$USER_MODEL->getAccessibleGroups()}
    {assign var=ASSIGNED_USER_ID value=$FIELD_MODEL->get('name')}
    {assign var=CURRENT_USER_ID value=$USER_MODEL->get('id')}
    {assign var=FIELD_VALUE value=$FIELD_MODEL->getEditViewDisplayValue($FIELD_MODEL->get('fieldvalue'))}

    {assign var=ACCESSIBLE_USER_LIST value=$USER_MODEL->getAccessibleUsersForModule($MODULE)}
    {assign var=ACCESSIBLE_GROUP_LIST value=$USER_MODEL->getAccessibleGroupForModule($MODULE)}
    <select class="select2 {$ASSIGNED_USER_ID}" data-name="{$ASSIGNED_USER_ID}" name="{$ASSIGNED_USER_ID}_value[]" data-fieldinfo='{$FIELD_INFO}' multiple style="width: {if $VIEW eq 'Edit'}60%{else}70%{/if}">
        <optgroup label="{vtranslate('LBL_USERS')}">
            {foreach key=USER_ID item=USER_NAME from=$ALL_ACTIVEUSER_LIST}
                <option value="{$USER_ID}" data-picklistvalue= '{$USER_NAME}'{foreach item=USERN key=USER from=$FIELD_VALUE}{if $USER eq $USER_ID } selected {/if}{/foreach}
                        {if array_key_exists($USER_ID, $ACCESSIBLE_USER_LIST)} data-recordaccess=true {else} data-recordaccess=false {/if}
                        data-userId="{$CURRENT_USER_ID}">
                    {$USER_NAME}
                </option>
            {/foreach}
        </optgroup>
        <optgroup label="{vtranslate('LBL_GROUPS')}">
            {foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEGROUP_LIST}
                <option value="{$OWNER_ID}" data-picklistvalue= '{$OWNER_NAME}' {foreach item=USERN key=USER from=$FIELD_VALUE}{if $USER eq $OWNER_ID } selected {/if}{/foreach}
                        {if array_key_exists($OWNER_ID, $ACCESSIBLE_GROUP_LIST)} data-recordaccess=true {else} data-recordaccess=false {/if} >
                    {$OWNER_NAME}
                </option>
            {/foreach}
        </optgroup>
    </select>
    {*<input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}[]' data-prev-value='{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}' />*}
    {*{else}*}
    {*{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}*}
    {*{/if}*}
{/strip}