<?php

class Assets_ActionAjax_Action extends Vtiger_Action_Controller
{
    public function checkPermission(Vtiger_Request $request)
    {
    }
    public function __construct()
    {
        parent::__construct();
        $this->exposeMethod("recalculate");
    }
    public function recalculate(Vtiger_Request $request)
    {
        global $adb;
        $module = $request->get("module");
        $recordId = $request->get('record');
        $assetInstance = Vtiger_Record_Model::getInstanceById($recordId, 'Assets');
        $assetInstance->set('mode', 'edit');
        $assetInstance->set('cf_1282', $assetInstance->get('cf_1382'));
        $assetInstance->set('cf_1284', $assetInstance->get('cf_1384'));
        $assetInstance->set('cf_1286', $assetInstance->get('cf_1386'));
        $assetInstance->set('cf_1288', $assetInstance->get('cf_1388'));
        $assetInstance->set('cf_1290', $assetInstance->get('cf_1390'));
        $assetInstance->set('cf_1292', $assetInstance->get('cf_1392'));
        $assetInstance->set('cf_1296', $assetInstance->get('cf_1394'));
        $assetInstance->set('cf_1298', $assetInstance->get('cf_1398'));

        $sql = "SELECT salesorderid FROM vtiger_salesorder INNER JOIN vtiger_crmentity ON vtiger_salesorder.salesorderid = vtiger_crmentity.crmid WHERE vtiger_crmentity.deleted = 0 AND vtiger_salesorder.cf_assets_id = ?";
        $re = $adb->pquery($sql, array($recordId));
        $assetModule = new Assets_Module_Model();
        if (0 < $adb->num_rows($re)) {
            for ($i = 0; $i < $adb->num_rows($re); $i++) {
                $salesorderid = $adb->query_result($re, $i, 'salesorderid');
                $soInstance = Vtiger_Record_Model::getInstanceById($salesorderid, 'SalesOrder');
                $products = $soInstance->getProducts();
                $sostatus = $soInstance->get('sostatus');
                switch ($sostatus) {
                    case 'Delivery Done':
                        $assetInstance = $assetModule->substractProductsFromAsset($products, $assetInstance, false);
                        break;
                    case 'Payment made':
                        $assetInstance = $assetModule->addAmountToAsset($soInstance->get('hdnGrandTotal'), $assetInstance, false);
                        break;
                    case 'Paid Delivered':
                        $assetInstance = $assetModule->substractProductsFromAsset($products, $assetInstance, false);
                        $assetInstance = $assetModule->addAmountToAsset($soInstance->get('hdnGrandTotal'), $assetInstance, false);
                        break;
                    case 'Closed':
                        $assetInstance = $assetModule->substractProductsFromAsset($products, $assetInstance, false);
                        $assetInstance = $assetModule->addAmountToAsset($soInstance->get('hdnGrandTotal'), $assetInstance, false);
                        break;
                }
            }
        }

        $sql = "SELECT purchaseorderid FROM vtiger_purchaseorder INNER JOIN vtiger_crmentity ON vtiger_purchaseorder.purchaseorderid = vtiger_crmentity.crmid WHERE vtiger_crmentity.deleted = 0 AND vtiger_purchaseorder.cf_assets_id = ?";
        $re = $adb->pquery($sql, array($recordId));
        if (0 < $adb->num_rows($re)) {
            for ($i = 0; $i < $adb->num_rows($re); $i++) {
                $purchaseorderid = $adb->query_result($re, $i, 'purchaseorderid');
                $poInstance = Vtiger_Record_Model::getInstanceById($purchaseorderid, 'PurchaseOrder');
                $products = $poInstance->getProducts();
                $postatus = $poInstance->get('postatus');

                switch ($postatus) {
                    case 'Delivery Made':
                        $assetInstance = $assetModule->addProductsInAsset($products, $assetInstance, false);
                        break;
                    case 'Payment made':
                        $assetInstance = $assetModule->removeAmountFromAsset($poInstance->get('hdnGrandTotal'), $assetInstance, false);
                        break;
                    case 'Delivered and Paid':
                        $assetInstance = $assetModule->addProductsInAsset($products, $assetInstance, false);
                        $assetInstance = $assetModule->removeAmountFromAsset($poInstance->get('hdnGrandTotal'), $assetInstance, false);
                        break;
                    case 'Closed':
                        $assetInstance = $assetModule->addProductsInAsset($products, $assetInstance, false);
                        $assetInstance = $assetModule->removeAmountFromAsset($poInstance->get('hdnGrandTotal'), $assetInstance, false);
                        break;
                }
            }
        }
        $assetInstance->save();

        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult(array("message" => "LBL_SUCCESS_RECALCULATE"));
        $response->emit();
    }

    public function process(Vtiger_Request $request)
    {
        $mode = $request->get("mode");
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
        }
    }
}

?>