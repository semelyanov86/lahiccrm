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

{if $SELECTABLE_WIDGETS|count gt 0}
	<button class='btn btn-default addButton dropdown-toggle' data-toggle='dropdown' >
		{vtranslate('LBL_ADD_WIDGET')}&nbsp;&nbsp;<i class="caret"></i>
	</button>

	<ul class="dropdown-menu dropdown-menu-right widgetsList pull-right" style="min-width:125%;text-align:left; margin-top: 13%;max-height: 500px;max-width: 250px; overflow: scroll">
		{assign var="MINILISTWIDGET" value=""}
		<li style="font-weight: bold;padding: 4px 6px;">{vtranslate('LBL_STANDARD_WIDGETS',$MODULE_NAME)}</li>
		<li class="divider"></li>
		{if $SELECTABLE_WIDGETS['other']}
		{foreach from=$SELECTABLE_WIDGETS['other'] item=WIDGET}
			{if $WIDGET->getName() != 'MiniList' && $MODULE_NAME == 'VReports'}
				<li>
					<a name="{$WIDGET->getName()}" onclick="VReports_DashBoard_Js.addWidget(this, '{$WIDGET->getUrl()}')" href="javascript:void(0);"
					   data-linkid="{$WIDGET->get('linkid')}" data-name="{$WIDGET->getName()}" data-width="{$WIDGET->getWidth()}" data-height="{$WIDGET->getHeight()}">
						{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</a>
				</li>
			{else}
				<li>
					<a name="{$WIDGET->getName()}" onclick="VReports_DashBoard_Js.addMiniListWidget(this, '{$WIDGET->getUrl()}')" href="javascript:void(0);"
					   data-linkid="{$WIDGET->get('linkid')}" data-name="{$WIDGET->getName()}" data-width="{$WIDGET->getWidth()}" data-height="{$WIDGET->getHeight()}">
						{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</a>
				</li>
			{/if}
		{/foreach}
		{else}
			<var style="padding: 4px 6px;" class="italic_small_size">No Records</var>
		{/if}

		<li class="divider"></li>
		<li style="font-weight: bold;padding: 4px 6px;">{vtranslate('LBL_CHART_REPORTS',$MODULE_NAME)}</li>
		{if $SELECTABLE_WIDGETS['myWidget']}
		{foreach from=$SELECTABLE_WIDGETS['myWidget'] item=WIDGET}
			{if $WIDGET->getName() eq 'MiniList'}
				{assign var="MINILISTWIDGET" value=$WIDGET} {* Defer to display as a separate group *}
			{else}
				<li class="{if $WIDGET->get('is_show') == true}hide{/if}" data-id="{{$WIDGET->get('reportid')}}" >
					<a onclick="VReports_DashBoard_Js.addWidget(this, '{$WIDGET->getUrl()}')" href="javascript:void(0);"
						data-linkid="{$WIDGET->get('linkid')}" data-name="{$WIDGET->getName()}" data-width="{$WIDGET->getWidth()}" data-height="{$WIDGET->getHeight()}">
						{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</a>
				</li>
			{/if}
		{/foreach}
		{else}
			<var style="padding: 4px 6px;" class="italic_small_size">No Records</var>
		{/if}

		<li class="divider"></li>
		<li style="font-weight: bold;padding: 4px 6px;">{vtranslate('LBL_DETAIL_REPORTS',$MODULE_NAME)}</li>
		{if $SELECTABLE_WIDGETS['detail']}
		{foreach from=$SELECTABLE_WIDGETS['detail'] item=WIDGET}
			<li>
				<a onclick="VReports_DashBoard_Js.addWidget(this, '{$WIDGET->getUrl()}')" href="javascript:void(0);"
				   data-linkid="{$WIDGET->get('linkid')}" data-name="{$WIDGET->getName()}" data-width="{$WIDGET->getWidth()}" data-height="{$WIDGET->getHeight()}">
					{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</a>
			</li>
		{/foreach}
		{else}
			<var style="padding: 4px 6px;" class="italic_small_size">No Records</var>
		{/if}

		<li class="divider"></li>
		<li style="font-weight: bold;padding: 4px 6px;">{vtranslate('LBL_SHARED_REPORTS',$MODULE_NAME)}</li>
		{if $SELECTABLE_WIDGETS['share']}
		{foreach from=$SELECTABLE_WIDGETS['share'] item=WIDGET}
				<li>
					<a onclick="VReports_DashBoard_Js.addWidget(this, '{$WIDGET->getUrl()}')" href="javascript:void(0);"
					   data-linkid="{$WIDGET->get('linkid')}" data-name="{$WIDGET->getName()}" data-width="{$WIDGET->getWidth()}" data-height="{$WIDGET->getHeight()}">
						{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</a>
				</li>
		{/foreach}
		{else}
			<var style="padding: 4px 6px;" class="italic_small_size">No Records</var>
		{/if}
	</ul>
{elseif $MODULE_PERMISSION}
	<button class='btn btn-default addButton dropdown-toggle' disabled="disabled" data-toggle='dropdown'>
		<strong>{vtranslate('LBL_ADD_WIDGET')}</strong> &nbsp;&nbsp;
		<i class="caret"></i>
	</button>
{/if}
