<?php
require_once 'modules/Assets/models/Module.php';

function CreateDiscountForPO($ws_entity){
    // WS id
    $taraCode = '19but';
    $ws_id = $ws_entity->getId();
    $module = $ws_entity->getModuleName();
    if (empty($ws_id) || empty($module)) {
        return;
    }

    // CRM id
    $crmid = vtws_getCRMEntityId($ws_id);

    if ($crmid <= 0) {
        return;
    }
    $assetModule = Vtiger_Module_Model::getInstance('Assets');
    $poModel = Vtiger_Record_Model::getInstanceById($crmid, 'PurchaseOrder');
    $potentialid = $poModel->get('cf_potentials_id');
    if (!$potentialid) {
        return false;
    }
    $potentialInstance = Vtiger_Record_Model::getInstanceById($potentialid, 'Potentials');
    $isDeposit = $potentialInstance->get('cf_1332');
    if (!$isDeposit) {
        return false;
    }
    $products = $poModel->getProducts();
    $taraId = false;
    $taraDiscount = 0;
    for ($i = 1; $i <= count($products); $i++) {
        if ($products[$i]['hdnProductcode' . $i] == $taraCode){
            $taraId = $products[$i]['hdnProductId' . $i];
            $taraDiscount = $products[$i]['discount_percent' . $i];
            $taraTotal = $products[$i]['productTotal' . $i];
        }
    }
    if ($taraDiscount > 90) {
        return true;
    }
    if ($taraId) {
        $assetModule->updateDiscount($taraId, $crmid, $poModel, $taraTotal);
    }
//    var_dump($products);die;
}


if (!function_exists('vtws_getCRMEntityId')) {
    function vtws_getCRMEntityId($elementid) {
        list ($typeId, $id) = vtws_getIdComponents($elementid);
        return $id;
    }
}
