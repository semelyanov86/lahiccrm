<?php


function CreatePOfromSO($ws_entity){
    global $adb;
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
    $contactId = $soInstance->get('contact_id');
    if (!$contactId || $contactId < 1) {
        $potentialId = $soInstance->get('potential_id');
        $potentialInstance = Vtiger_Record_Model::getInstanceById($potentialId, 'Potentials');
        $contactId = $potentialInstance->get('contact_id');
    }
    $contactInstance = Vtiger_Record_Model::getInstanceById($contactId, 'Contacts');
    $oldRequest = $_REQUEST;
    $qty = $soInstance->get('cf_1341');
    if ($soInstance && $qty > 0) {
        $poInstance = Vtiger_Record_Model::getCleanInstance('PurchaseOrder');
        $poInstance->set('mode', 'create');
        $poInstance->set('vendor_id', '95681');
        $poInstance->set('postatus', 'Delivered and Paid');
        $poInstance->set('assigned_user_id', $soInstance->get('assigned_user_id'));
        $poInstance->set('description', $soInstance->get('description'));
        $poInstance->set('subject', 'Возврат ' . $soInstance->get('subject'));
        $poInstance->set('cf_potentials_id', $soInstance->get('potential_id'));
        $poInstance->set('cf_assets_id', $soInstance->get('cf_assets_id'));
        $poInstance->set('contact_id', $soInstance->get('contact_id'));
        $poInstance->set('cf_nrl_salesorder459_id', $crmid);
        $poInstance->set('bill_street', $contactInstance->get('mailingstreet'));
        $poInstance->set('ship_street', $contactInstance->get('otherstreet'));
        $poInstance->set('bill_city', $contactInstance->get('mailingcity'));
        $poInstance->set('ship_city', $contactInstance->get('othercity'));
        $poInstance->set('bill_state', $contactInstance->get('mailingstate'));
        $poInstance->set('ship_state', $contactInstance->get('otherstate'));
        $poInstance->set('bill_code', $contactInstance->get('mailingzip'));
        $poInstance->set('ship_code', $contactInstance->get('otherzip'));
        $poInstance->set('bill_country', $contactInstance->get('mailingcountry'));
        $poInstance->set('cf_1337', $contactInstance->get('cf_1273'));
        $poInstance->set('cf_1339', $contactInstance->get('cf_1277'));
        $poInstance->set('ship_country', $contactInstance->get('othercountry'));
        $product = Vtiger_Record_Model::getInstanceById(12, 'Products');
        $price = $product->get('unit_price');
        $total = $price * $qty;
        for ($i = 1; $i <= 10; $i++) {
        /*    if ($i == 1) {
                $_REQUEST['hdnProductId1'] = 12;
                $_REQUEST['lineItemType1'] = 'Products';
                $_REQUEST['qty1'] = $qty;
                $_REQUEST['purchaseCost1'] = $product->get('purchase_cost');
                $_REQUEST['productName1'] = 'Бутыль 19л';
                $_REQUEST['listPrice1'] = $price;
                $_REQUEST['total'] = $total;
                $_REQUEST['subtotal'] = $total;
                $_REQUEST['currency_id'] = 2;
            } else {*/
                unset($_REQUEST['productName' . $i]);
                unset($_REQUEST['hdnProductId' . $i]);
                unset($_REQUEST['lineItemType' . $i]);
                unset($_REQUEST['subproduct_ids' . $i]);
                unset($_REQUEST['comment' . $i]);
                unset($_REQUEST['qty' . $i]);
                unset($_REQUEST['purchaseCost' . $i]);
                unset($_REQUEST['margin' . $i]);
                unset($_REQUEST['listPrice' . $i]);
                unset($_REQUEST['discount_type' . $i]);
                unset($_REQUEST['discount' . $i]);
                unset($_REQUEST['discount_percentage' . $i]);
                unset($_REQUEST['discount_amount' . $i]);
//            }
        }
        $poInstance->save();
        $sql= $adb->pquery("INSERT INTO vtiger_inventoryproductrel SET id=?,productid=?,sequence_no=?,quantity=?,listprice=?",array($poInstance->getId(),12,1,$qty,$price));
        $sql= $adb->pquery("UPDATE vtiger_purchaseorder SET total=?,subtotal=?,currency_id=? WHERE purchaseorderid=?",array($total,$total,2,$poInstance->getId()));
        $_REQUEST = $oldRequest;
        ProcessDoublePurchaseStatus($poInstance->getId());
        CalcDoubleOstatokFromPO($poInstance->getId());

    }

}


if (!function_exists('vtws_getCRMEntityId')) {
    function vtws_getCRMEntityId($elementid) {
        list ($typeId, $id) = vtws_getIdComponents($elementid);
        return $id;
    }
}

function ProcessDoublePurchaseStatus($crmid){

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

function CalcDoubleOstatokFromPO($crmid){

    if ($crmid <= 0) {
        return;
    }

    //получение объекта со всеми данными о текущей записи Модуля "SalesOrder"
    $poInstance = Vtiger_Record_Model::getInstanceById($crmid);

    $dealId = $poInstance->get('cf_potentials_id');

    $soModule = new SalesOrder_Module_Model();

    if($dealId) {
        $soModule->calcOstatok($dealId);
    }
}
