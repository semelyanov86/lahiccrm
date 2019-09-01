<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

include_once 'include/Webservices/Utils.php';
include_once 'include/Webservices/ModuleTypes.php';
include_once 'include/Webservices/Revise.php';
set_time_limit(10000);
ini_set('display_errors','on'); version_compare(PHP_VERSION, '5.5.0') <= 0 ? error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED) : error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);   // DEBUGGING

class Leads_RemoveDublicates_Handler {

    public function removeDublicates($data){
        global $site_URL;
        global $adb;
        $query = "SELECT leadid FROM vtiger_leaddetails INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_leaddetails.leadid WHERE vtiger_crmentity.deleted = 0";
        $result = $adb->pquery($query, array());
        $numOfRows = $adb->num_rows($result);
        $mapping = array();
        for ($i=0; $i<$numOfRows; $i++) {
            $rowData = $adb->query_result_rowdata($result, $i);
            $mapping[] = $rowData['leadid'];
        }
        $this->findNumbers($mapping);
        echo 'DONE!';
    }

    private function findNumbers($ids)
    {
        global $adb;
        foreach ($ids as $id) {
            $leadModel = Vtiger_Record_Model::getInstanceById($id, 'Leads');
            $phone = $leadModel->get('phone');
            $dublModels = $this->phoneModels($phone);
            if ($dublModels) {
                $this->deleteDubl($dublModels);
            }
        }
    }

    private function phoneModels($phone)
    {
        global $adb;
        $query = "SELECT leadaddressid FROM vtiger_leadaddress INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_leadaddress.leadaddressid WHERE vtiger_crmentity.deleted = 0 AND vtiger_leadaddress.phone = ?";
        $result = $adb->pquery($query, array($phone));
        $numOfRows = $adb->num_rows($result);
        $res = array();
        if ($numOfRows > 1){
            for ($i=0; $i<$numOfRows; $i++) {
                $rowData = $adb->query_result_rowdata($result, $i);
                $res[] = Vtiger_Record_Model::getInstanceById($rowData['leadaddressid'], 'Leads');
            }
        } else {
            return false;
        }
        return $res;
    }

    private function deleteDubl($phoneModels)
    {
        for ($i=0; $i<count($phoneModels); $i++) {
            if ($i == (count($phoneModels) - 1)) {
                echo 'Deleted: ' . $phoneModels[$i]->getId() . '<br>';
                $phoneModels[$i]->delete();
                return true;
            } else {
                if (!$phoneModels[$i]->get('leadsource') && $phoneModels[$i]->get('assigned_user_id') == '1') {
                    echo 'Deleted: ' . $phoneModels[$i]->getId() . '<br>';
                    $phoneModels[$i]->delete();
                    return true;
                }
            }
        }
        return false;
    }

}