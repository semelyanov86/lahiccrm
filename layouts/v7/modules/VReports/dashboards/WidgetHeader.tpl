{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{strip}
    <div style="padding-top: 5px; padding-bottom: 5px;">
    <span style="font-size: 14px; font-weight:bold ">
        {if $WIDGET->get('title')}
            {textlength_check($WIDGET->get('title'))}
            {else}
            {textlength_check($TITLE)}
        {/if}

    </span>
    <span style="float:right;font-size: 14px">
        {if $WIDGET->get('shared') != true}
            {*replace icon setting -> wrench in history widget*}
            {if $TITLE == 'History'}
                <a class="fa fa-wrench action-widget-header" hspace="2" border="0" align="absmiddle" title="Edit" alt="Edit" style="display: none;" data-event="Edit" data-show="show" onclick="VReports_DashBoard_Js.registerFilterInitiater(this)"></a>&nbsp;&nbsp;
                <a class="fa fa-cog action-widget-header" hspace="2" border="0" align="absmiddle" title="Setting" alt="Setting" style="display: none;" data-event="Setting" onclick="VReports_DashBoard_Js.eventActionHeaderWidget(this)"></a>&nbsp;&nbsp;
            {else}
                {if $WIDGET_NAME == 'MiniList'}
                    <span class="page-number hide" style="margin-left: 50px;">
                        <span class="page-numbers" >1 to {$RECORD_COUNTS}</span>
                            &nbsp;{if $RECORD_COUNTS neq $ALL_RECORD_COUNTS || $RECORD_COUNTS < $ALL_RECORD_COUNTS}
                                <input type="hidden" name="all_record_counts" value="{$ALL_RECORD_COUNTS}">
                                <input type="hidden" name="page_limit" value="{$PAGE_LIMIT}">
                                <span class="totalNumberOfRecords cursorPointer" title="Click for this list size">of
                                    &nbsp;<i class="fa fa-question showTotalCountIcon" onclick="VReports_DashBoard_Js.eventShowCount(this,{$ALL_RECORD_COUNTS},{$PAGE_LIMIT})"></i>
                                </span>
                            {/if}&nbsp;&nbsp;&nbsp;
                    </span>
                {/if}

                <a class="fa fa-cog action-widget-header" hspace="2" border="0" align="absmiddle" title="Setting" alt="Setting" style="display: none;" data-event="Setting" onclick="VReports_DashBoard_Js.eventActionHeaderWidget(this)"></a>&nbsp;&nbsp;
            {/if}
            <a class="fa fa-refresh action-widget-header" hspace="2" border="0" align="absmiddle" title="Refresh" alt="Refresh" style="display: none;" data-event="Refresh" onclick="VReports_DashBoard_Js.eventActionHeaderWidget(this)"></a>&nbsp;&nbsp;
            {if $WIDGET->get('report') == true}
            <a class="fa fa-eye action-widget-header" data-event="detail" onclick="VReports_DashBoard_Js.eventActionHeaderWidget(this)" style="margin-right: 5px; display: none;"></a>
            <a class="fa fa-pencil action-widget-header" data-event="edit" onclick="VReports_DashBoard_Js.eventActionHeaderWidget(this)" style="margin-right: 5px; display: none;"></a>
            {/if}
            <a class="fa fa-close action-widget-header" style="display: none;" data-id="{{$WIDGET->get('reportid')}}" data-event="delete" onclick="VReports_DashBoard_Js.eventActionHeaderWidget(this)"></a>
        {/if}
        </span>
    </div>
{/strip}