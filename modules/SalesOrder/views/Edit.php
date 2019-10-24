<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Class SalesOrder_Edit_View extends Inventory_Edit_View {

    public function checkPermission(Vtiger_Request $request) {
        parent::checkPermission($request);
        $record = $request->get('record');
        if ($record) {
            $this->checkOrderStatus($record);
        }
    }

    protected function checkOrderStatus(string $record)
    {
        $recordModel = Vtiger_Record_Model::getInstanceById($record, 'SalesOrder');
        $status = $recordModel->get('sostatus');
        if ($status == 'Closed') {
            $userModel = Users_Record_Model::getCurrentUserModel();
            if (!$userModel->isAdminUser()) {
                throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
            }
        }
    }

	public function process(Vtiger_Request $request) {
		parent::process($request);
	}
}