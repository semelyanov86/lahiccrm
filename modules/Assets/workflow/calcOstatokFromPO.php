<?php

require_once 'modules/SalesOrder/models/Module.php';

function CalcOstatokFromPO($ws_entity){
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
    $poInstance = Vtiger_Record_Model::getInstanceById($crmid);

    $dealId = $poInstance->get('cf_potentials_id');

    $soModule = new SalesOrder_Module_Model();

    if($dealId) {
        $soModule->calcOstatok($dealId);
    }
}


if (!function_exists('vtws_getCRMEntityId')) {
    function vtws_getCRMEntityId($elementid) {
        list ($typeId, $id) = vtws_getIdComponents($elementid);
        return $id;
    }
}

