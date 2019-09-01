<?php
 /*+*******************************************************************************
 * The content of this file is subject to the CRMTiger Pro license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vTiger
 * The Modified Code of the Original Code owned by https://crmtiger.com/
 * Portions created by CRMTiger.com are Copyright(C) CRMTiger.com
 * All Rights Reserved.
  ***************************************************************************** */
include_once dirname(__FILE__) . '/FetchRecord.php';

class CTMobile_WS_GlobalSearch extends CTMobile_WS_FetchRecord {
	
	function process(CTMobile_API_Request $request) {
		global $adb;
		global $current_user; // Required for vtws_update API
		$current_user = $this->getActiveUser();
		$searchKey = trim($request->get('value'));
		$searchModule = false;
			
		if($request->get('searchModule')) {
			$searchModule = trim($request->get('searchModule'));
		}
		$matchingRecordsList = array();
		$matchingRecords =  Vtiger_Record_Model::getSearchResult($searchKey, $searchModule);
		foreach ($matchingRecords as $module => $recordModelsList) {
			foreach($recordModelsList as $key => $value){
				$crmid = $value->get('crmid');
				$label = $value->get('label');
				$createdtime = Vtiger_Util_Helper::formatDateDiffInStrings($value->get('createdtime'));
				if($module == 'Calendar' || $module == 'Events'){
					$EventTaskQuery = $adb->pquery("SELECT * FROM  `vtiger_activity` WHERE activitytype = ? AND activityid = ?",array('Task',$crmid)); 
					if($adb->num_rows($EventTaskQuery) > 0){
						$wsid = CTMobile_WS_Utils::getEntityModuleWSId('Calendar');
						$recordid = $wsid.'x'.$crmid;
						$recordModule = 'Calendar';
					}else{
						$wsid = CTMobile_WS_Utils::getEntityModuleWSId('Events');
						$recordid = $wsid.'x'.$crmid;
						$recordModule = 'Events';
					}
				}else{
					$recordModule = $module;
					$wsid = CTMobile_WS_Utils::getEntityModuleWSId($recordModule);
					$recordid = $wsid.'x'.$crmid;
				}
				$moduleLabel = vtranslate($recordModule,$recordModule);
				$recordArray = array('record'=>$recordid,'label'=>$label,'module'=>$recordModule,'moduleLabel'=>$moduleLabel,'createdtime'=>$createdtime);
				$matchingRecordsList[vtranslate($module,$module)][] = $recordArray;
			}
			
		}
		$response = new CTMobile_API_Response();
		if(count($matchingRecordsList) == 0){
			$message = vtranslate('No records found for','CTMobile').' "'.$searchKey.'"';
			$response->setResult(array('code'=>404,'message'=>$message));
		}else{
			$response->setResult($matchingRecordsList);
		}
		return $response;
		
	}
}
