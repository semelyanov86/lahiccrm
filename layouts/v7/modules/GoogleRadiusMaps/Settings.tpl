{*<!--
/* ********************************************************************************
* The content of this file is subject to the Profit Calculator ("License");
* You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is VTExperts.com
* Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
* All Rights Reserved.
* ****************************************************************************** */
-->*}
<div class="detailViewContainer summaryWidgetContainer">
    <div class="editViewPageDiv">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="contents">
                <div class="form-horizontal">
                    <h4>{vtranslate('GoogleRadiusMaps', 'GoogleRadiusMaps')}</h4>
                    <hr> <br>
                    <div class="detailViewInfo">
                        <div class="row form-group">
                            <div class="col-lg-4 control-label fieldLabel">
                                <label>Enable</label>
                            </div>
                            <div class="col-lg-4 input-group">
                                <input type="checkbox" name="enable_module" id="enable_module" value="1" {if $ENABLE eq '1'}checked="" {/if}/>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-4 control-label fieldLabel">
                                <label>{vtranslate('LBL_MAP_CENTER', 'GoogleRadiusMaps')}</label>
                            </div>
                            <div class="col-lg-4 input-group">
                                <select class="select2" id="slbMapCenter">
                                    <option value="Company Address" {if $MAP_CENTER eq 'Company Address'}selected {/if}>Company Address</option>
                                    <option value="User Address" {if $MAP_CENTER eq 'User Address'}selected {/if}>User Address</option>
                                    <option value="Current Location" {if $MAP_CENTER eq 'Current Location'}selected {/if}>Current Location</option>
                                    <option value="Zip Code" {if $MAP_CENTER eq 'Zip Code'}selected {/if}>Zip Code</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-4 control-label fieldLabel">
                                <label>{vtranslate('LBL_RADIUS', 'GoogleRadiusMaps')}</label>
                            </div>
                            <div class="col-lg-4 input-group">
                                <select class="select2" id="slbRadiusUnit">
                                    <option value="miles" {if $RADIUS_UNIT eq 'miles'}selected {/if}>miles</option>
                                    <option value="km" {if $RADIUS_UNIT eq 'km'}selected {/if}>km</option>
                                </select>
                                <input style="margin-left: 10px;max-width: 100px;display: inline-block;position: absolute;padding: 0px 3px;" class="inputElement" type="number" id="txtRadiusNumber" value="{$RADIUS_NUMBER}"/>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-4 control-label fieldLabel">
                                <label>{vtranslate('LBL_ENABLE_ON', 'GoogleRadiusMaps')}
                                    <span data-toggle="tooltip" data-html="true" title="{vtranslate('LBL_ENABLE_ON_TOOLTIP', 'GoogleRadiusMaps')}">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                </label>
                            </div>
                            <div class="col-lg-4 input-group">
                                {assign var=ALL_MODULES value=Settings_ModuleManager_Module_Model::getAll()}
                                {assign var=FIELD_VALUE value=','|explode:$ENABLE_ON}
                                <select class="select2 inputElement" id="chkEnableOn" multiple>
                                    {foreach from=$ALL_MODULES item=MODULE}
                                        {assign var=TAB_ID value=$MODULE->getId()}
                                        {assign var=MODULE_LABEL value=$MODULE->getName()}
                                        {assign var=MODULE_LABEL value=vtranslate($MODULE_LABEL, 'Vtiger')}
                                        <option value="{$TAB_ID}"
                                                {foreach item=F_VALUE from=$FIELD_VALUE}{if $F_VALUE eq $TAB_ID } selected {/if}{/foreach} >{$MODULE_LABEL}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-4 control-label fieldLabel">
                                <label>{vtranslate('Is default view?', 'GoogleRadiusMaps')}</label>
                            </div>
                            <div class="col-lg-4 input-group">
                                <input type="checkbox" name="is_default_view" id="is_default_view" value="1" {if $IS_DEFAULT_VIEW eq '1'}checked="" {/if}/>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-4 control-label fieldLabel">
                                <label></label>
                            </div>
                            <div class="col-lg-4 input-group">
                                <button class="btn btn-success" name="btnGRMSettingSave" id="btnGRMSettingSave" type="button"><strong>{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</strong></button>
                            </div>
                        </div>
                        {if !$HAS_GOOGLE_ADDRESS}
                        <div class="row form-group">
                            <div class="col-lg-6 col-lg-offset-2">
                                <span style="color: red; font-size: 80px; float: left; display: inline-flex;"><i class="fa fa-warning"></i></span>
                                <p style="font-size: 14px;color: red;padding-left: 90px;">{vtranslate('LBL_HAS_NOT_GOOGLE_ADDRESS', 'GoogleRadiusMaps')}</p>
                            </div>
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>