<?php
require_once 'modules/Assets/models/Module.php';

function CountOrders($ws_entity){
    // WS id
    global $VTIGER_BULK_SAVE_MODE;
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
    $assetInstance = Vtiger_Record_Model::getInstanceById($crmid);

    $assetModule = new Assets_Module_Model();
    $previousBulkSaveMode = $VTIGER_BULK_SAVE_MODE;
    $VTIGER_BULK_SAVE_MODE = true;

    $assetModule->countPOandSO($assetInstance);

    $VTIGER_BULK_SAVE_MODE = $previousBulkSaveMode;

}


if (!function_exists('vtws_getCRMEntityId')) {
    function vtws_getCRMEntityId($elementid) {
        list ($typeId, $id) = vtws_getIdComponents($elementid);
        return $id;
    }
}
