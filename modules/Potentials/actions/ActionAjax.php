<?php

class Potentials_ActionAjax_Action extends Vtiger_Action_Controller
{
    public function checkPermission(Vtiger_Request $request)
    {
    }
    public function __construct()
    {
        parent::__construct();
        $this->exposeMethod("recalculateBottles");
    }
    public function recalculateBottles(Vtiger_Request $request)
    {
        global $adb;
        $module = $request->get("module");
        $recordId = $request->get('record');
        $soModule = new SalesOrder_Module_Model();
        $soModule->calcOstatok($recordId);

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