{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{strip}
    <div class='tab-content contentsBackground hide' style="height:auto;padding: 1%; background-color: white;">
        <br>
        <div class="row tab-pane active">
            <div>
                <span class="col-lg-3">
                    <div><span>{vtranslate('LBL_DISPLAY_GRID', $MODULE)}</span></div><br>
                    <div class="row">
                        <select id='display_grid' name='displaygrid'
                                style='min-width:300px;' class="select2 ">
                            <option value="0"
                                    {if $CHART_MODEL->get('displaygrid') == '0'}selected="selected"{/if}>{vtranslate('No', $MODULE)}</option>
                            <option value="1"
                                    {if $CHART_MODEL->get('displaygrid') == '1'}selected="selected"{/if}>{vtranslate('Yes', $MODULE)}</option>
                            </select>
                    </div>
                    <br>
                    <div><span>{vtranslate('LBL_DISPLAY_LABEL', $MODULE)}</span></div><br>
                    <div class="row">
                        <select id='display_label'
                                data-value="{$CHART_MODEL->get('displaylabel')}"
                                name='displaylabel' style='min-width:300px;'
                                class="select2">
                            <option value="0"
                                    {if $CHART_MODEL->get('displaylabel') == '0'}selected="selected"{/if}>{vtranslate('No', $MODULE)}</option>
                            <option value="1"
                                    {if $CHART_MODEL->get('displaylabel') == '1' || $CHART_MODEL->get('displaylabel') != '0'}selected="selected"{/if}>{vtranslate('Yes', $MODULE)}</option>
                            </select>
                    </div>
                </span>
                <span class="col-lg-2">&nbsp;</span>
                <span class="col-lg-3">
                    {*Show Legend Number*}
                    <br>
                    <div><span>{vtranslate('LBL_LEGEND_VALUE', $MODULE)}</span></div><br>
                    <div class="row">
                        <select id='legendvalue' name='legendvalue' style='min-width:300px;'
                                class="select2">
                            <option value="0"
                                    {if $CHART_MODEL->get('legendvalue') == '0'}selected="selected"{/if}>{vtranslate('No', $MODULE)}</option>
                            <option value="1"
                                    {if $CHART_MODEL->get('legendvalue') == '1'}selected="selected"{/if}>{vtranslate('Yes', $MODULE)}</option>
                            <option value="2" {if $CHART_MODEL->get('legendvalue') == '2'}selected="selected"{/if}>{vtranslate('Yes - Value (Percentage)', $MODULE)}</option>
                            <option value="3" {if $CHART_MODEL->get('legendvalue') == '3'}selected="selected"{/if}>{vtranslate('Yes - Value', $MODULE)}</option>
                            <option value="4" {if $CHART_MODEL->get('legendvalue') == '4'}selected="selected"{/if}>{vtranslate('Yes - Percentage', $MODULE)}</option>
                        </select>
                    </div>
                    {*end cShow Legend Number*}
                    {*custom format large numbers*}
                    <br>
                    <div><span>{vtranslate('LBL_FOTMAT_LARGE_MUNBER', $MODULE)}</span></div><br>
                    <div class="row">
                        <select id='formatlargenumber' name='formatlargenumber'
                                style='min-width:300px;' class="select2">
                            <option value="0"
                                    {if $CHART_MODEL->get('formatlargenumber') == '0'}selected="selected"{/if}>{vtranslate('No', $MODULE)}</option>
                            <option value="1"
                                    {if $CHART_MODEL->get('formatlargenumber') == '1'}selected="selected"{/if}>{vtranslate('Yes', $MODULE)}</option>
                            </select>
                    </div>
                    {*end custom format large numbers*}
                    {*Draw Horizontal Line*}
                    <br>
                    <div class="label-drawline" {if $CHART_MODEL->getChartType() != 'barChart' && $CHART_MODEL->getChartType() != 'horizontalBarChart' && $CHART_MODEL->getChartType() != 'stackedChart' && $CHART_MODEL->getChartType() != 'barFunnelChart'} style="display: none" {/if}><span>{vtranslate('Draw Horizontal Line', $MODULE)}</span></div><br>
                    <div class="row input-drawline" {if $CHART_MODEL->getChartType() != 'barChart' && $CHART_MODEL->getChartType() != 'horizontalBarChart' && $CHART_MODEL->getChartType() != 'stackedChart' && $CHART_MODEL->getChartType() != 'barFunnelChart'} style="display: none" {/if}>
                        <input type="number" id='drawline' name='drawline'
                               style='max-width:300px;' class="inputElement"
                               value="{$CHART_MODEL->get('drawline')}"/>
                    </div>
                    {*end Draw Horizontal Line*}
                </span>
            </div>
        </div>
        <br><br>
        <div class='row alert-info' style="padding: 20px;">
            <span class='span alert-info'>
                <span>
                    <i class="fa fa-info-circle"></i>&nbsp;&nbsp;&nbsp;
                    {vtranslate('LBL_PLEASE_SELECT_ATLEAST_ONE_GROUP_FIELD_AND_DATA_FIELD', $MODULE)}
                    {vtranslate('LBL_FOR_BAR_GRAPH_AND_LINE_GRAPH_SELECT_3_MAX_DATA_FIELDS', $MODULE)}
                </span>
            </span>
        </div>
    </div>
{/strip}