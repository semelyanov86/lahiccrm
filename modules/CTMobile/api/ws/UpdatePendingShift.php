<?php
 /*+*******************************************************************************
 * The content of this file is subject to the CRMTiger Pro license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vTiger
 * The Modified Code of the Original Code owned by https://crmtiger.com/
 * Portions created by CRMTiger.com are Copyright(C) CRMTiger.com
 * All Rights Reserved.
  ***************************************************************************** */

class CTMobile_WS_UpdatePendingShift extends CTMobile_WS_Controller {
	function process(CTMobile_API_Request $request) {
		global $current_user; 
		$current_user = $this->getActiveUser();
		
		$module = 'CTAttendance';
		
		$attendance_status = trim($request->get('attendance_status'));
		$employee_name = trim($request->get('userid'));
		$latitude = trim($request->get('latitude'));
		$longitude = trim($request->get('longitude'));
		$checkin_status = trim($request->get('checkin_status'));
		
		$response = new CTMobile_API_Response();
		
		if (empty($attendance_status)) {
			$message = vtranslate('Status cannot be empty!'=>'CTMobile');
			$response->setError(1501, $message);
			return $response;
		}
		
		if (empty($employee_name)) {
			$message = vtranslate('User cannot be empty!'=>'CTMobile');
			$response->setError(1501, $message);
			return $response;
		}
		
		if (empty($latitude)) {
			$message = vtranslate('Latitude cannot be empty!'=>'CTMobile');
			$response->setError(1501, $message);
			return $response;
		}
			
		if (empty($longitude)) {
			$message = vtranslate('Longitude cannot be empty!'=>'CTMobile');
			$response->setError(1501, $message);
			return $response;
		}
			
		if($checkin_status == 'Expire') {
			global $adb;
			$getAttendanceQuery = $adb->pquery("SELECT * FROM vtiger_ctattendance INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_ctattendance.ctattendanceid where vtiger_crmentity.deleted = 0 AND vtiger_ctattendance.attendance_status = 'check_in' AND vtiger_ctattendance.employee_name = ?", array($employee_name));
			$numOfRows = $adb->num_rows($getAttendanceQuery);

			if($numOfRows > 0) {
				for($i=0;$i<$numOfRows;$i++){
					$attendanceid = $adb->query_result($getAttendanceQuery, $i, 'ctattendanceid');
					$attendanceRecorddModel = Vtiger_Record_Model::getInstanceById($attendanceid, $module);
					$attendanceRecorddModel->set('mode','edit');
					$attendanceRecorddModel->set('check_out_location',"$latitude,$longitude");
					$attendanceRecorddModel->set('attendance_status',$attendance_status);
					$attendanceRecorddModel->set('assigned_user_id',$current_user->id);
					$attendanceRecorddModel->save();
				}
				$response->setResult(array('status' => true));
			} else {
				$response->setResult(array('status' => false));
			}
		} else {
			$response->setResult(array('status' => false));
		}
		
		return $response;
	}
}
