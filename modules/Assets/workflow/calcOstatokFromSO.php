<?php

require_once 'modules/SalesOrder/models/Module.php';

function CalcOstatokFromSO($ws_entity){
    // WS id
    global $adb;
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

    $dealId = $soInstance->get('potential_id');

    $soModule = new SalesOrder_Module_Model();

    if($dealId) {
        $total = $soModule->calcOstatok($dealId);
        $adb->pquery("UPDATE vtiger_salesordercf SET cf_1346=? WHERE salesorderid=?",array($total,$crmid));
    }
}


if (!function_exists('vtws_getCRMEntityId')) {
    function vtws_getCRMEntityId($elementid) {
        list ($typeId, $id) = vtws_getIdComponents($elementid);
        return $id;
    }
}

