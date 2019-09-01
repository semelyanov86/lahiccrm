{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
{*<div style='padding-top: 0;margin-bottom: 2%;padding-right:15px;'>*}
    <input type="hidden" id="widget_{$WIDGET->get('id')}_currentPage" value="{$CURRENT_PAGE}">
    <input type="hidden" name="widgetId" value="{$WIDGET->get('id')}">
    {* Comupte the nubmer of columns required *}
    {assign var="SPANSIZE" value=12}
    {assign var=HEADER_COUNT value=$MINILIST_WIDGET_MODEL->getHeaderCount()}
    {if $HEADER_COUNT}
        {assign var="SPANSIZE" value=12/$HEADER_COUNT}
    {/if}
    <table name="miniListTable" data-module-name="{$SELECTED_MODULE_NAME}" style="width: 100%;white-space: nowrap;text-align: left">
        <input type="hidden" id="record-counts-listview" value="{$RECORD_COUNTS}">
        <thead>
        <tr style="width: 90%;height: 20px; font-size: 11px">
            {*<div class="row" style="padding:5px">*}
            <td class="fake-head" colspan="2"></td>
                {assign var=HEADER_FIELDS value=$MINILIST_WIDGET_MODEL->getHeaders()}
                {assign var=LISTVIEW_HEADERS value=$MINILIST_WIDGET_MODEL->getHeaders()}
                {include file="PicklistColorMap.tpl"|vtemplate_path:$BASE_MODULE}
                {foreach item=FIELD from=$HEADER_FIELDS}
                    <td style="padding-left: 10px;
                        {if $FIELD->get('uitype') eq 72 || $FIELD->get('uitype') eq 7 ||
                        $FIELD->get('uitype') eq 83 || $FIELD->get('uitype') eq 9 || $FIELD->get('uitype') eq 71}
                            {if !$smarty.foreach.minilistWidgetModelRowHeaders.first}
                                    text-align: right;
                            {/if}
                        {/if}
                    ">
                        <var class="italic_small_size">{vtranslate($FIELD->get('label'),$BASE_MODULE)}</var>
                    </td>
                {/foreach}
            {*</div>*}
        </tr>
        </thead>

        {assign var="MINILIST_WIDGET_RECORDS" value=$MINILIST_WIDGET_MODEL->getRecords()}
        <tbody>
        {if empty($MINILIST_WIDGET_RECORDS)}
            <tr>
                <td colspan="{$HEADER_COUNT+2}" style="text-align: center">{vtranslate('LBL_NO_RECORDS_FOUND')}</td>
            </tr>
        {/if}
        {if $MINILIST_WIDGET_RECORDS}
        {foreach item=RECORD from=$MINILIST_WIDGET_RECORDS}
            {assign var="RAW_DATA_MINILIST" value=$RECORD->getRawData()}
            {if $RECORD->get('hrOnRow') eq true}
            <tr class="lineOnRow">
                <td colspan="{$HEADER_COUNT+2}">
                    <hr>
                </td>
            </tr>
            {/if}
            <tr class="miniListContent" style="height: 18px;">
                {*<div class="row miniListContent" style="padding:5px">*}
                {foreach item=FIELD_MODEL key=FIELD_NAME from=$HEADER_FIELDS name="minilistWidgetModelRowHeaders"}
                    {if $smarty.foreach.minilistWidgetModelRowHeaders.first}
                        {include file="layouts/v7/modules/VReports/dashboards/MiniListRecordAction.tpl"}
                    {/if}
                        <td class="col-lg-{$SPANSIZE} textOverflowEllipsis" title="{strip_tags($RAW_DATA_MINILIST[$FIELD_NAME])}"
                            style="padding-right: 5px;padding-left: 10px;
                                {if $FIELD_MODEL->get('uitype') eq 72 || $FIELD_MODEL->get('uitype') eq 7 ||
                                $FIELD_MODEL->get('uitype') eq 83 || $FIELD_MODEL->get('uitype') eq 9 || $FIELD_MODEL->get('uitype') eq 71}
                                    {if !$smarty.foreach.minilistWidgetModelRowHeaders.first}
                                        text-align: right;
                                    {/if}
                                {/if}
                                    ">
                            {*<div class="col-lg-{$SPANSIZE} textOverflowEllipsis" title="{strip_tags($RECORD->get(FIELD_NAME))}"*}
                                 {*style="padding-right: 5px;">*}
                            {assign var=UITYPE value=array(2,4,22,25,10,51,57,59,73,75,76,77,78,80,81)}
                                {if $FIELD_MODEL->get('uitype') eq '71' || ($FIELD_MODEL->get('uitype') eq '72' && $FIELD_MODEL->getName() eq 'unit_price')}
                                    {assign var=CURRENCY_ID value=$USER_MODEL->get('currency_id')}
                                    {if $FIELD_MODEL->get('uitype') eq '72' && $FIELD_NAME eq 'unit_price'}
                                        {assign var=CURRENCY_ID value=getProductBaseCurrency($RECORD_ID, $RECORD->getModuleName())}
                                    {/if}
                                    {assign var=CURRENCY_INFO value=getCurrencySymbolandCRate($CURRENCY_ID)}
                                    {if $RECORD->get($FIELD_NAME) neq NULL}
                                        &nbsp;{CurrencyField::appendCurrencySymbol($RECORD->get($FIELD_NAME), $CURRENCY_INFO['symbol'])}&nbsp;
                                    {/if}
                                {elseif $FIELD_MODEL->get('uitype') eq '15' || $FIELD_MODEL->get('uitype') eq '16'}
                                    {assign var=PICKLIST_COLOR_MAP value=Settings_Picklist_Module_Model::getPicklistColorMap($FIELD_NAME, true)}

                                     {*{assign var=PICKLIST_COLOR value=$PICKLIST_COLOR_MAP[$RECORD->get($FIELD_NAME)]}*}
                                    {assign var=PICKLIST_COLOR value=Settings_Picklist_Module_Model::getPicklistColorByValue($FIELD_MODEL->getName(), $RECORD->get($FIELD_NAME))}

                                    {assign var=PICKLIST_TEXT_COLOR value=decode_html(Settings_Picklist_Module_Model::getTextColor($PICKLIST_COLOR))}
                                    <span class="picklist-color picklist-{$FIELD_MODEL->getId()}-{Vtiger_Util_Helper::convertSpaceToHyphen($RECORD->get($FIELD_NAME))}" style="background-color: {$PICKLIST_COLOR};">{$RECORD->get($FIELD_NAME)}</span>
                                {elseif $FIELD_MODEL->get('uitype') eq '33' || $FIELD_MODEL->getFieldDataType() eq 'multipicklist'}
                                    {assign var=MULTI_RAW_PICKLIST_VALUES value=explode('|##|',{$RECORD->get($FIELD_NAME)})}
                                    {assign var=MULTI_PICKLIST_VALUES value=explode(',',{$RECORD->get($FIELD_NAME)})}
                                    {assign var=ALL_MULTI_PICKLIST_VALUES value=array_flip($FIELD_MODEL->getPicklistValues())}
                                    {foreach item=MULTI_PICKLIST_VALUE key=MULTI_PICKLIST_INDEX from=$MULTI_PICKLIST_VALUES}
                                        <span {if ($RECORD->get($FIELD_NAME)) neq ''} class="picklist-color picklist-{$FIELD_MODEL->getId()}-{Vtiger_Util_Helper::convertSpaceToHyphen(trim($ALL_MULTI_PICKLIST_VALUES[trim($MULTI_PICKLIST_VALUE)]))}"{/if} >
													{if trim($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX]) eq vtranslate('LBL_NOT_ACCESSIBLE', $MODULE)}
                                                        <font color="red">
														{trim($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX])}
														</font>
                                                    {else}
                                                        {trim($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX])}
                                                    {/if}
                                            {if !empty($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX + 1])},{/if}
												</span>
                                    {/foreach}
                                {elseif ($FIELD_MODEL->getFieldName() eq 'lastname' || in_array($FIELD_MODEL->get('uitype'),$UITYPE)) && $RECORD->get($FIELD_NAME) != '--'}
                                    <a class="js-reference-display-value" target="_blank" href="{$RECORD->getDetailViewUrl()}">{$RECORD->get($FIELD_NAME)}&nbsp;</a>
                                {else}
                                    {$RECORD->get($FIELD_NAME)}
                                {/if}
                            {*</div>*}
                        </td>
                {/foreach}
                {*</div>*}
            </tr>
        {/foreach}
        {/if}
        </tbody>
    </table>
    {if $MORE_EXISTS}
        <div class="moreLinkDiv" style="padding-top:10px;padding-bottom:5px;">
            <a class="miniListMoreLink" data-linkid="{$WIDGET->get('linkid')}" data-widgetid="{$WIDGET->get('id')}"
               onclick="VReports_DashBoard_Js.registerMoreClickEvent(event);">{vtranslate('LBL_MORE')}...</a>
        </div>
    {/if}
{*</div>*}