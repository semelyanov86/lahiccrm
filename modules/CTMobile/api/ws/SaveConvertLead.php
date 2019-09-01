<?php
/*+*******************************************************************************
 * The content of this file is subject to the CRMTiger Pro license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vTiger
 * The Modified Code of the Original Code owned by https://crmtiger.com/
 * Portions created by CRMTiger.com are Copyright(C) CRMTiger.com
 * All Rights Reserved.
  ***************************************************************************** */
vimport('~~/include/Webservices/ConvertLead.php');
include_once dirname(__FILE__) . '/FetchRecordWithGrouping.php';

class CTMobile_WS_SaveConvertLead extends CTMobile_WS_FetchRecordWithGrouping {

	function process(CTMobile_API_Request $request) {
		global $adb,$current_user; // Required for vtws_update API
		$current_user = $this->getActiveUser();
		$roleid = $current_user->roleid;
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$moduleName = $request->get('module');
		$record = explode('x',$request->get('record'));
		$recordId = $record[1];
		$modules = $request->get('modules');
		$assignId = $request->get('assigned_user_id');

		$entityValues = array();
		$entityValues['transferRelatedRecordsTo'] = $request->get('transferModule');
		$entityValues['assignedTo'] = vtws_getWebserviceEntityId(vtws_getOwnerType($assignId), $assignId);
		$entityValues['leadId'] =  vtws_getWebserviceEntityId($moduleName, $recordId);
		$entityValues['imageAttachmentId'] = $request->get('imageAttachmentId');

		$recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
		$convertLeadFields = $recordModel->getConvertLeadFields();

		$availableModules = array('Accounts', 'Contacts', 'Potentials');
		foreach ($availableModules as $module) {
			if(vtlib_isModuleActive($module)&& in_array($module, $modules)) {
				$entityValues['entities'][$module]['create'] = true;
				$entityValues['entities'][$module]['name'] = $module;

				// Converting lead should save records source as CRM instead of WEBSERVICE
				$entityValues['entities'][$module]['source'] = 'CRM';
				foreach ($convertLeadFields[$module] as $fieldModel) {
					$fieldName = $fieldModel->getName();
					$fieldValue = $request->get($fieldName);

					//Potential Amount Field value converting into DB format
					if ($fieldModel->getFieldDataType() === 'currency') {
					if($fieldModel->get('uitype') == 72){
						// Some of the currency fields like Unit Price, Totoal , Sub-total - doesn't need currency conversion during save
						$fieldValue = Vtiger_Currency_UIType::convertToDBFormat($fieldValue, null, true);
					} else {
						$fieldValue = Vtiger_Currency_UIType::convertToDBFormat($fieldValue);
					}
					} elseif ($fieldModel->getFieldDataType() === 'date') {
						$fieldValue = DateTimeField::convertToDBFormat($fieldValue);
					} elseif ($fieldModel->getFieldDataType() === 'reference' && $fieldValue) {
						$ids = vtws_getIdComponents($fieldValue);
						if (count($ids) === 1) {
							$fieldValue = vtws_getWebserviceEntityId(getSalesEntityType($fieldValue), $fieldValue);
						}
					}
					$entityValues['entities'][$module][$fieldName] = $fieldValue;
				}
			}
		}

		$result = vtws_convertlead($entityValues, $currentUser);
		
		if(!empty($result['Accounts'])) {
			$accountIdComponents = vtws_getIdComponents($result['Accounts']);
			$accountId = $accountIdComponents[1];
		}
		if(!empty($result['Contacts'])) {
			$contactIdComponents = vtws_getIdComponents($result['Contacts']);
			$contactId = $contactIdComponents[1];
		}

		if(!empty($accountId)) {
			$transferModule = "Accounts";
			$wsId = CTMobile_WS_Utils::getEntityModuleWSId($transferModule);
			$transferRecordId = $$wsId.'x'.$accountId;
		} elseif (!empty($contactId)) {
			$transferModule = "Contacts";
			$wsId = CTMobile_WS_Utils::getEntityModuleWSId($transferModule);
			$transferRecordId = $$wsId.'x'.$contactId;
		}	

		$response = new CTMobile_API_Response();
		$response->setResult(array('transferModule'=>$transferModule,'recordid'=>$transferRecordId));
		return $response;
	}
}