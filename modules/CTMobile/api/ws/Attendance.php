<?php
 /*+*******************************************************************************
 * The content of this file is subject to the CRMTiger Pro license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vTiger
 * The Modified Code of the Original Code owned by https://crmtiger.com/
 * Portions created by CRMTiger.com are Copyright(C) CRMTiger.com
 * All Rights Reserved.
  ***************************************************************************** */
include_once dirname(__FILE__) . '/FetchRecordWithGrouping.php';

include_once 'include/Webservices/Create.php';
include_once 'include/Webservices/Update.php';

class CTMobile_WS_Attendance extends CTMobile_WS_FetchRecordWithGrouping {
	protected $recordValues = false;
	
	// Avoid retrieve and return the value obtained after Create or Update
	protected function processRetrieve(CTMobile_API_Request $request) {
		return $this->recordValues;
	}
	
	function process(CTMobile_API_Request $request) {
		global $current_user; // Required for vtws_update API
		$current_user = Users::getActiveAdminUser();
		$module = 'CTAttendance';
		$recordid = trim($request->get('record'));
		$attendance_status = trim($request->get('attendance_status'));
		$employee_name = trim($request->get('userid'));
		$latitude = trim($request->get('latitude'));
		$longitude = trim($request->get('longitude'));
		
		$response = new CTMobile_API_Response();
		
		if (empty($attendance_status)) {
			$message = vtranslate('Status cannot be empty!','CTMobile');
			$response->setError(1501, $message);
			return $response;
		}
		if (empty($employee_name)) {
			$message = vtranslate('User cannot be empty!','CTMobile');
			$response->setError(1501, $message);
			return $response;
		}
		if (empty($latitude)) {
			$message = vtranslate('Latitude cannot be empty!','CTMobile');
			$response->setError(1501, $message);
			return $response;
		}	
		if (empty($longitude)) {
			$message = vtranslate('Longitude cannot be empty!','CTMobile');
			$response->setError(1501, $message);
			return $response;
		}
		try {
			// Retrieve or Initalize
			if (!empty($recordid) && !$this->isTemplateRecordRequest($request)) {
				$this->recordValues = vtws_retrieve($recordid, $current_user);
			} else {
				$this->recordValues = array();
			}
			
			// Set the modified values
			
				$this->recordValues['attendance_status'] = trim($attendance_status);
				$this->recordValues['employee_name'] = '19x'.$employee_name;
				$this->recordValues['assigned_user_id'] = '19x'.$current_user->id;
				
				if($attendance_status == 'check_in'){
					$this->recordValues['check_in_location'] = "$latitude,$longitude";
				}elseif($attendance_status == 'check_out'){
					$this->recordValues['check_out_location'] = "$latitude,$longitude";
				}
			// Update or Create
			if (isset($this->recordValues['id'])) {
				if($attendance_status == 'check_out') {
					$recordId = explode('x',$this->recordValues['id']);
					$attendanceRecorddModel = Vtiger_Record_Model::getInstanceById($recordId[1], $module);
					$attendanceRecorddModel->set('mode','edit');
					$attendanceRecorddModel->set('check_out_location',"$latitude,$longitude");
					$attendanceRecorddModel->set('attendance_status',$attendance_status);
					$attendanceRecorddModel->set('assigned_user_id',$current_user->id);
					$attendanceRecorddModel->save();
					
				}else{
					$this->recordValues = vtws_update($this->recordValues, $current_user);
				}
			} else {
				$this->recordValues = vtws_create($module, $this->recordValues, $current_user);
			}
			
			// Update the record id
			$request->set('record', $this->recordValues['id']);
			
			// Gather response with full details
			$response = parent::process($request);
			
		} catch(Exception $e) {
			$response->setError($e->getCode(), $e->getMessage());
		}
		return $response;
	}
	
}
