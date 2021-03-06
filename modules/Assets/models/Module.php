<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Assets_Module_Model extends Vtiger_Module_Model {

	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if ($sourceModule == 'HelpDesk') {
			$condition = " vtiger_assets.assetsid NOT IN (SELECT relcrmid FROM vtiger_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM vtiger_crmentityrel WHERE relcrmid = '$record') ";

			$pos = stripos($listQuery, 'where');
			if ($pos) {
				$split = preg_split('/where/i', $listQuery);
				$overRideQuery = $split[0].' WHERE '.$split[1].' AND '.$condition;
			} else {
				$overRideQuery = $listQuery.' WHERE '.$condition;
			}
			return $overRideQuery;
		}
	}

	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return false;
	}

	/*
	 * Function to get supported utility actions for a module
	 */
	public function getUtilityActionsNames() {
		return array('Import', 'Export', 'DuplicatesHandling');
	}

    public function substractProductsFromAsset($products, $asset, $nosave = true) {
        if ($nosave) {
            $asset->set('mode', 'edit');
        }
        foreach ($products as $key => $product) {
            $code = $product['hdnProductcode' . $key];
            $fieldname = $this->getFieldNameByLabel($code, 48);
            $qty = $asset->get($fieldname) - $product['qty' . $key];
            $asset->set($fieldname, $qty);
        }
        if ($nosave) {
            $asset->save();
        } else {
            return $asset;
        }
    }

    public function addProductsInAsset($products, $asset, $nosave = true) {
	    if ($nosave) {
            $asset->set('mode', 'edit');
        }
        foreach ($products as $key => $product) {
            $code = $product['hdnProductcode' . $key];
            $fieldname = $this->getFieldNameByLabel($code, 48);
            $qty = $asset->get($fieldname) + $product['qty' . $key];
            $asset->set($fieldname, $qty);
        }
        if ($nosave) {
            $asset->save();
        } else {
            return $asset;
        }
    }

    public function addAmountToAsset($amount, $asset, $nosave = true) {
        if ($nosave) {
            $asset->set('mode', 'edit');
            $newAmount = CurrencyField::convertToUserFormat($asset->get('cf_1298'), null, false) + $amount;
        } else {
            $newAmount = $asset->get('cf_1298') + $amount;
        }
        $asset->set('cf_1298', $newAmount);
        if ($nosave) {
            $asset->save();
        } else {
            return $asset;
        }
    }

    public function removeAmountFromAsset($amount, $asset, $nosave = true) {
	    if ($nosave) {
            $asset->set('mode', 'edit');
            $newAmount = CurrencyField::convertToUserFormat($asset->get('cf_1298'), null, false) - $amount;
        } else {
            $newAmount = $asset->get('cf_1298') - $amount;
        }

        $asset->set('cf_1298', $newAmount);
        if($nosave) {
            $asset->save();
        } else {
            return $asset;
        }
    }

    function getFieldNameByLabel($fieldLabel, $tabId) {
        $db = PearDatabase::getInstance();
        $query = 'SELECT * FROM vtiger_field WHERE tabid IN ('.  generateQuestionMarks($tabId).') AND fieldlabel=?';
        $result = $db->pquery($query, array($tabId,$fieldLabel));
        if ($db->num_rows($result) > 0) {
            return $db->query_result($result,0,'fieldname');
        } else {
            return false;
        }
    }

    public function countPOandSO($asset)
    {
        if ($asset->getId() < 1) {
            return false;
        }
        $db = PearDatabase::getInstance();
        $asset->set('mode', 'edit');
        $query = 'SELECT COUNT(vtiger_salesorder.salesorderid) AS cnt FROM vtiger_salesorder INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_salesorder.salesorderid WHERE vtiger_crmentity.deleted = 0 AND vtiger_salesorder.cf_assets_id = ?';
        $result = $db->pquery($query, array($asset->getId()));
        if ($db->num_rows($result) > 0) {
            $asset->set('cf_1348', $db->query_result($result,0,'cnt'));
        }
        $query = 'SELECT COUNT(vtiger_purchaseorder.purchaseorderid) AS cnt FROM vtiger_purchaseorder INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_purchaseorder.purchaseorderid WHERE vtiger_crmentity.deleted = 0 AND vtiger_purchaseorder.cf_assets_id = ?';
        $result = $db->pquery($query, array($asset->getId()));
        if ($db->num_rows($result) > 0) {
            $asset->set('cf_1350', $db->query_result($result,0,'cnt'));
        }
        $doneOrders = 0;
        $query = "SELECT COUNT(vtiger_salesorder.salesorderid) AS cnt FROM vtiger_salesorder INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_salesorder.salesorderid WHERE vtiger_crmentity.deleted = 0 AND vtiger_salesorder.cf_assets_id = ? AND vtiger_salesorder.sostatus IN ('Delivery Done', 'Paid Delivered', 'Closed')";
        $result = $db->pquery($query, array($asset->getId()));
        if ($db->num_rows($result) > 0) {
            $doneOrders += $db->query_result($result,0,'cnt');
        }
        $query = "SELECT COUNT(vtiger_purchaseorder.purchaseorderid) AS cnt FROM vtiger_purchaseorder INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_purchaseorder.purchaseorderid WHERE vtiger_crmentity.deleted = 0 AND vtiger_purchaseorder.cf_assets_id = ? AND vtiger_purchaseorder.postatus IN ('Delivery Made', 'Delivered and Paid', 'Closed')";
        $result = $db->pquery($query, array($asset->getId()));
        if ($db->num_rows($result) > 0) {
            $doneOrders += $db->query_result($result,0,'cnt');
            $asset->set('cf_1352', $doneOrders);
        }
        $asset->save();
    }

    public function updateDiscount($taraId, $crmid, $module, $total)
    {
        global $adb;
        if ($taraId && $crmid) {
            $finalTotal = $module->get('hdnGrandTotal') - $total;
            $subTotal = $module->get('hdnSubTotal') - $total;
            $preTotal = $module->get('pre_tax_total') - $total;
            if ($finalTotal < 0 || $subTotal < 0) {
                return false;
            }
            $query = "UPDATE vtiger_inventoryproductrel SET vtiger_inventoryproductrel.discount_percent = ? WHERE vtiger_inventoryproductrel.id = ? AND vtiger_inventoryproductrel.productid = ?";
            $adb->pquery($query, array(100, $crmid, $taraId));
            if ($module->getModuleName() == "SalesOrder") {
                $query = "UPDATE vtiger_salesorder SET vtiger_salesorder.total = ?, vtiger_salesorder.subtotal = ?, vtiger_salesorder.pre_tax_total = ? WHERE vtiger_salesorder.salesorderid = ?";
            } else {
                $query = "UPDATE vtiger_purchaseorder SET vtiger_purchaseorder.total = ?, vtiger_purchaseorder.subtotal = ?, vtiger_purchaseorder.pre_tax_total = ? WHERE vtiger_purchaseorder.purchaseorderid = ?";
            }

            $adb->pquery($query, array($finalTotal, $subTotal, $preTotal, $crmid));
        } else {
            return false;
        }

    }
}
