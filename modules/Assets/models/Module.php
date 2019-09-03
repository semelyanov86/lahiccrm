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

    public function substractProductsFromAsset($products, $asset) {
        $asset->set('mode', 'edit');
        foreach ($products as $key => $product) {
            $code = $product['hdnProductcode' . $key];
            $fieldname = $this->getFieldNameByLabel($code, 48);
            $qty = $asset->get($fieldname) - $product['qty' . $key];
            $asset->set($fieldname, $qty);
        }
        $asset->save();
    }

    public function addProductsInAsset($products, $asset) {
        $asset->set('mode', 'edit');
        foreach ($products as $key => $product) {
            $code = $product['hdnProductcode' . $key];
            $fieldname = $this->getFieldNameByLabel($code, 48);
            $qty = $asset->get($fieldname) + $product['qty' . $key];
            $asset->set($fieldname, $qty);
        }
        $asset->save();
    }

    public function addAmountToAsset($amount, $asset) {
        $asset->set('mode', 'edit');
        $newAmount = CurrencyField::convertToUserFormat($asset->get('cf_1298'), null, false) + $amount;
        $asset->set('cf_1298', $newAmount);
        $asset->save();
    }

    public function removeAmountFromAsset($amount, $asset) {
        $asset->set('mode', 'edit');
        $newAmount = CurrencyField::convertToUserFormat($asset->get('cf_1298'), null, false) - $amount;
        $asset->set('cf_1298', $newAmount);
        $asset->save();
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
}
