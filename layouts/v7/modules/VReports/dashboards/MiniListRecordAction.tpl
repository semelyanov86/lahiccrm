{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}

<td class="fake-body" style="padding-left: 5px;width: 25px;">
    <span style="opacity: 0.7" class="action">
        <a class="quickView fa fa-eye icon action" data-module-name="{$RECORD->getModuleName()}" style="padding-right: 5px;"
           data-id="{$RECORD->get('id')}" data-app="TOOLS" title="{vtranslate('LBL_QUICK_VIEW', $MODULE)}"></a>
    </span>
</td>
{*Minilists - Low Pri
<td class="fake-body" style="padding-left: 3px">
    <a href="{$RECORD->getDetailViewUrl()}" class="pull-left"><i
                title="{vtranslate('LBL_SHOW_COMPLETE_DETAILS',$MODULE_NAME)}"
                class="fa fa-list"></i></a>
</td>*}

<td class="fake-body" style="padding-left: 3px;width: 15px">

    <span style="opacity: 0.7" class="more dropdown action">
         <span href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;padding-right: 5px;">
             <i class="fa fa-ellipsis-v icon"></i>
         </span>
         <ul class="dropdown-menu"
             style="top:auto;width: auto;height: auto;position: absolute" data-id="{$RECORD->get('id')}">
             <style>
                 .dropdown-menu>li>a.standard-btn{
                     padding: 3px 5px !important;
                 }
                 .dropdown-menu>li>a.standard-btn:hover {
                     background-image: -webkit-linear-gradient(top, #8FB0CF 0, #8FB0CF 100%);
                 }
             </style>
             <li style="padding: 0px;position: relative;border: none">
                 <a class="standard-btn" data-id="{$RECORD->get('id')}" href="{$RECORD->getFullDetailViewUrl()}" style="padding: 0px 6px;">
                     <i class="fa fa-square-o"></i>&nbsp;&nbsp;{vtranslate('LBL_DETAILS')}
                 </a>
             </li>
             <li style="padding: 0px;position: relative;border: none">
                 <a class="standard-btn" data-id="{$RECORD->get('id')}" href="javascript:void(0);" data-url="{$RECORD->getEditViewUrl()}" name="editlink" style="padding: 0px 6px;" target="_blank">
                     <i class="fa fa-pencil"></i>&nbsp;&nbsp;{vtranslate('LBL_EDIT')}
                 </a>
             </li>
             {if $RECORD->get('VTEButton')}
                 <hr>
                 {foreach from = $RECORD->get('VTEButton') item = BUTTON}
                     <style>
                         .p-o-vtebtn{$BUTTON['vtebuttonsid']}:hover {
                             background-color: #{$BUTTON['color']} !important;
                             color: #FFFFFF !important;
                         }
                     </style>
                     <li style="padding: 0;position: relative;border: none">
                         <a href="javascript:void(0);" data-module="{$RECORD->getModuleName()}"
                            data-button-id="{$BUTTON['vtebuttonsid']}" data-record-id="{$RECORD->get('id')}"
                            class="vteButtonQuickUpdateVReport p-o-vtebtn{$BUTTON['vtebuttonsid']}"
                            style="color: #{$BUTTON['color']};
                                    {*border: thin solid #{$BUTTON['color']} !important;*}
                                    /*border-radius: 2px;*/
                                 background-image: none !important;
                                 box-shadow: none !important;
                                 /*line-height: 18px;*/
                                 cursor: pointer;
                                 /*font-weight: 400;*/
                                 /*padding: 6px 16px !important;*/
                                 /*margin: 0px 4px!important;*/
                                 background-color: white; "><i class="icon-module {$BUTTON['icon']}" style="font-size: 12px;"></i>&nbsp;&nbsp;{$BUTTON['header']}</a></li>
                 {/foreach}
             </ul>
        </span>
    {/if}
</td>