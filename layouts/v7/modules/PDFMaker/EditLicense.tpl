{*<!--
/* * *******************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}
{strip}
<div class="modal-dialog">
	<div class="modal-content">
        {if $TYPE eq "reactivate"}
            {assign var=HEADER_TITLE value=vtranslate('LBL_REACTIVATE', 'PDFMaker')}
        {else}
            {assign var=HEADER_TITLE value=vtranslate('LBL_ACTIVATE_KEY', 'PDFMaker')}
        {/if}
        {include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
		<form id="editLicense" class="form-horizontal contentsBackground">
                    <input type="hidden" name="module" value="PDFMaker">
                    <input type="hidden" name="action" value="License">
                    <input type="hidden" name="mode" value="editLicense">
                    <input type="hidden" name="type" value="{$TYPE}">
                    <div class="modal-body">
						<table class="massEditTable table no-border">
							<tr>
   								<td class="fieldLabel col-lg-2">
									<label class="muted pull-right">{vtranslate('LBL_LICENSE_KEY', 'PDFMaker')}
											<span class="redColor">*</span>
									</label>
								</td>
								<td class="fieldValue col-lg-4" colspan="3">
									<input type="text" class="inputElement" name="licensekey" value="{$LICENSEKEY}" data-validation-engine='validate[required]' /></td>
							</tr>
						</table>
                    </div>
            {assign var=BUTTON_ID value="js-edit-license"}
			{include file='ModalFooter.tpl'|@vtemplate_path:'Vtiger'}
		</form>
	</div>
</div>
{/strip}