<?php

function ProcessPurchaseStatus($ws_entity){
    // WS id
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

    //получение объекта со всеми данными о текущей записи Модуля "PurchaseOrder"
    $poInstance = Vtiger_Record_Model::getInstanceById($crmid);

    $products = $poInstance->getProducts();
    //получение id Актива
    $assetId = $poInstance->get('cf_assets_id');

    $assetModule = new Assets_Module_Model();

    if($assetId) {
        //получение объекта со всеми данными о текущей записи Модуля "Актив"
        $assetInstance = Vtiger_Record_Model::getInstanceById($assetId);
        // получение статуса заказа
        $postatus = $poInstance->get('postatus');

        switch ($postatus) {
            case 'Delivery Made':
                $assetModule->addProductsInAsset($products, $assetInstance);
                break;
            case 'Payment made':
                $assetModule->removeAmountFromAsset($poInstance->get('hdnGrandTotal'), $assetInstance);
                break;
            case 'Delivered and Paid':
                $assetModule->addProductsInAsset($products, $assetInstance);
                $assetModule->removeAmountFromAsset($poInstance->get('hdnGrandTotal'), $assetInstance);
                break;
            default:
//                echo "Informed status";
        }


    }
}


if (!function_exists('vtws_getCRMEntityId')) {
    function vtws_getCRMEntityId($elementid) {
        list ($typeId, $id) = vtws_getIdComponents($elementid);
        return $id;
    }
}

