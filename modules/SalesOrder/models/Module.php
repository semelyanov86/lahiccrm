<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class SalesOrder_Module_Model extends Inventory_Module_Model{

    public function calcOstatok($id)
    {
        global $adb;
        $query = "SELECT salesorderid FROM `vtiger_salesorder` INNER JOIN vtiger_crmentity ON vtiger_salesorder.salesorderid = vtiger_crmentity.crmid WHERE vtiger_crmentity.deleted = 0 AND vtiger_salesorder.potentialid = ? AND (`sostatus` = 'Paid Delivered' OR `sostatus` = 'Delivery Done' OR `sostatus` = 'Payment made' OR `sostatus` = 'Closed')";
        $result = $adb->pquery($query, array($id));
        $salesList = array();
        $soSum = 0;
        $poSum = 0;
        if ($result && $adb->num_rows($result)) {
            while ($rowData = $adb->fetch_row($result)) {
                $salesList[] = $rowData['salesorderid'];
            }
        }
        if (count($salesList) > 0) {
            $addDeal = implode(",",$salesList);
            $query = "SELECT SUM(quantity) as qty FROM `vtiger_inventoryproductrel` WHERE id IN ($addDeal) AND productid = ?";
            $soResult = $adb->pquery($query, array(12));
            $soRow = $adb->num_rows($soResult);
            if($soRow) {
                $soSum = $adb->query_result($soResult, 0, 'qty');
            }
        }

        $query = "SELECT purchaseorderid FROM `vtiger_purchaseorder` INNER JOIN vtiger_crmentity ON vtiger_purchaseorder.purchaseorderid = vtiger_crmentity.crmid WHERE vtiger_crmentity.deleted = 0 AND vtiger_purchaseorder.cf_potentials_id = ? AND (`postatus` = 'Delivered and Paid' OR `postatus` = 'Delivery Made' OR `postatus` = 'Payment made' OR `postatus` = 'Closed')";
        $result = $adb->pquery($query, array($id));
        $purchaseList = array();
        if ($result && $adb->num_rows($result)) {
            while ($rowData = $adb->fetch_row($result)) {
                $purchaseList[] = $rowData['purchaseorderid'];
            }
        }
        if (count($purchaseList) > 0) {
            $addDeal = implode(",",$purchaseList);
            $query = "SELECT SUM(quantity) as qty FROM `vtiger_inventoryproductrel` WHERE id IN ($addDeal) AND productid = ?";
            $poResult = $adb->pquery($query, array(12));
            $poRow = $adb->num_rows($poResult);
            if($poRow) {
                $poSum = $adb->query_result($poResult, 0, 'qty');
            }
        }
        $total = $soSum - $poSum;
        $poModel = Vtiger_Record_Model::getInstanceById($id, 'Potentials');
        $poModel->set('mode', 'edit');
        $poModel->set('cf_1344', $total);
        $poModel->save();
        return $total;

    }

}
?>
