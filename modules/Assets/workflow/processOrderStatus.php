<?php

require_once 'modules/Assets/models/Module.php';

function ProcessOrderStatus($ws_entity){
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

    //получение объекта со всеми данными о текущей записи Модуля "SalesOrder"
    $soInstance = Vtiger_Record_Model::getInstanceById($crmid);

    $products = $soInstance->getProducts();
    //получение id Актива
    $assetId = $soInstance->get('cf_assets_id');

    $assetModule = new Assets_Module_Model();

    if($assetId) {
        //получение объекта со всеми данными о текущей записи Модуля "Актив"
        $assetInstance = Vtiger_Record_Model::getInstanceById($assetId);
        // получение статуса заказа
        $sostatus = $soInstance->get('sostatus');

        switch ($sostatus) {
            case 'Delivery Done':
                $assetModule->substractProductsFromAsset($products, $assetInstance);
                break;
            case 'Payment made':
                $assetModule->addAmountToAsset($soInstance->get('hdnGrandTotal'), $assetInstance);
                break;
            case 'Paid Delivered':
                $assetModule->substractProductsFromAsset($products, $assetInstance);
                $assetModule->addAmountToAsset($soInstance->get('hdnGrandTotal'), $assetInstance);
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

