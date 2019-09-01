<?php
/*+*******************************************************************************
 * The content of this file is subject to the CRMTiger Pro license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vTiger
 * The Modified Code of the Original Code owned by https://crmtiger.com/
 * Portions created by CRMTiger.com are Copyright(C) CRMTiger.com
 * All Rights Reserved.
  ***************************************************************************** */
class CTMobileSettings_SaveLicense_Action extends Settings_Vtiger_Basic_Action {
    
public function process(Vtiger_Request $request) {
	global $adb,$site_URL;
	$getLicenseQuery=$adb->pquery("SELECT * FROM ctmobile_license_settings");
	$numOfLicenseCount = $adb->num_rows($getLicenseQuery);
	$License_Key = trim($request->get('license_key'));
	$numofUsers = CTMobileSettings_Module_Model::getTotalCrmUsers();
	$url = CTMobileSettings_Module_Model::$CTMOBILE_CHECKLICENSE_URL;
	$ch = curl_init($url);
	$data = array( "license_key"=>$License_Key,"domain"=>$site_URL,"action"=>"activate",'num_of_crm_user'=>$numofUsers);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result == 'validated'){
		$url = CTMobileSettings_Module_Model::$CTMOBILE_CHECKLICENSE_URL;
		$ch = curl_init($url);
		// Setup request to send json via POST.
		$data2 = array( "license_key"=>$License_Key,"domain"=>$site_URL,"action"=>"get_licence_data");
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data2 );
		// Return response instead of printing.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Send request.
		$result2 = curl_exec($ch);
		curl_close($ch);
		$licencedata = json_decode($result2);
		$premiumUserType = array('Monthly','One Time','Yearly');
		$user_type = $licencedata->user_type;
		$expirydate = $licencedata->expirydate;
		if(in_array($user_type,$premiumUserType)){
			$user_type = 'premium';
		}else{
			$user_type = 'free';
		}
		if($numOfLicenseCount > 0){
			$record=$adb->query_result($getLicenseQuery,0,'id');
			$query=$adb->pquery("UPDATE ctmobile_license_settings SET license_key=?, domain=?, expirydate=?, user_type=? WHERE id=?",array($License_Key, $site_URL, $expirydate, $user_type, $record));
			if($query){
				$result = array('code'=>2, 'msg'=>vtranslate('License Key Updated Successfully','CTMobileSettings'));
			}
		}else{
			$query=$adb->pquery("INSERT INTO ctmobile_license_settings (license_key,status,domain,expirydate,user_type) values(?,?,?,?,?)",array($License_Key,1,$site_URL,$expirydate,$user_type));
			if($query){
				$result = array('code'=>1, 'msg'=>vtranslate('License Key Inserted Successfully','CTMobileSettings'));
			}
		}
		global $adb,$root_directory;
		$doc_root = $_SERVER['DOCUMENT_ROOT'];
		$url = CTMobileSettings_Module_Model::$CTMOBILE_VERSION_URL;
        $ch = curl_init($url);
		$data = array( "vt_version"=>'7.x');
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$results = curl_exec($ch);
		curl_close($ch);
		$jason_result = json_decode($results);
		$zip_url = $jason_result->ext_path;
		$ext_version = $jason_result->ext_version;
		mkdir($root_directory."/test/".$ext_version, 0777);
		$destination_path = $root_directory."/test/".$ext_version."/CTMobileupgrade.zip";
		file_put_contents($destination_path, fopen($zip_url, 'r'));
		chmod($root_directory."/test/".$ext_version."/CTMobileupgrade.zip",0755);
		$zip = new ZipArchive;
		$res = $zip->open($root_directory."/test/".$ext_version."/CTMobileupgrade.zip");
		if ($res === TRUE) {
			$zip->extractTo($root_directory."/test/".$ext_version."/");
			$zip->close();
		}
		$package = new Vtiger_Package();
		if(!getTabid('CTMobile')){
			$package->import($root_directory."/test/".$ext_version.'/CTMobile.zip',true);
		}else{
			$package->update(Vtiger_Module::getInstance('CTMobile'),$root_directory."/test/".$ext_version.'/CTMobile.zip');
		}
		if(!getTabid('CTAttendance')){
			$package->import($root_directory."/test/".$ext_version.'/CTAttendance.zip',true);
		}else{
			$package->update(Vtiger_Module::getInstance('CTAttendance'),$root_directory."/test/".$ext_version.'/CTAttendance.zip');
		}
		if(!getTabid('CTMessageTemplate')){
			$package->import($root_directory."/test/".$ext_version.'/CTMessageTemplate.zip',true);
		}else{
			$package->update(Vtiger_Module::getInstance('CTMessageTemplate'),$root_directory."/test/".$ext_version.'/CTMessageTemplate.zip');
		}
		if(!getTabid('CTPushNotification')){
			$package->import($root_directory."/test/".$ext_version.'/CTPushNotification.zip',true);
		}else{
			$package->update(Vtiger_Module::getInstance('CTPushNotification'),$root_directory."/test/".$ext_version.'/CTPushNotification.zip');
		}
		if(!getTabid('CTUserFilterView')){
			$package->import($root_directory."/test/".$ext_version.'/CTUserFilterView.zip',true);
		}else{
			$package->update(Vtiger_Module::getInstance('CTUserFilterView'),$root_directory."/test/".$ext_version.'/CTUserFilterView.zip');
		}
		
		global $root_directory;	
		$array = array('CTAttendance','CTMessageTemplate','CTMobile','CTPushNotification','CTUserFilterView','CTMobileSettings');
		foreach ($array as $key => $value) {
			$path  = $root_directory.'modules/'.$value;
    		chmod($path, 0755);
    		$path  = $root_directory.'layouts/v7/modules/'.$value;
    		chmod($path, 0755);
        } 
		$exsting_path = $root_directory.'/test/'.$ext_version.'/api.php';
		$new_path = $root_directory.'/api.php';
		$upload_status =  copy($exsting_path, $new_path);
	}else if($result == 'Already activated'){
		$result = array('code'=>101, 'msg'=>vtranslate('You Enetered License is Already Registered','CTMobileSettings'));
	}else{
		$result = array('code'=>100, 'msg'=>vtranslate('You Enetered License is Invalid','CTMobileSettings'));
	}
	$response = new Vtiger_Response();
	$response->setEmitType(Vtiger_Response::$EMIT_JSON);
	$response->setResult($result);
	$response->emit();
}
}
?>
