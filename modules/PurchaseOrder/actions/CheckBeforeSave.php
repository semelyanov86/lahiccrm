<?php
class SalesOrder_CheckBeforeSave_Action extends Vtiger_Action_Controller {

    function checkPermission(Vtiger_Request $request) {
        return;
    }

    public function process(Vtiger_Request $request) {
        $dataArr = $request->get('checkBeforeSaveData');;
        $response = "OK";
        $message = "";
        $selected_status = $dataArr['postatus'];

        if($request->get('editViewAjaxMode')) {
            $mode = $request->get('createMode');

            // On create or edit
            if (isset($mode) && (($mode == 'create') || ($mode == 'edit'))) {
                if($selected_status == 'Closed') {
                    $userModel = Users_Record_Model::getCurrentUserModel();
                    $currole = $userModel->getRole();
                    if ($currole != 'H2' && $currole != 'H7') {
                        $response = "ALERT";
                        $message = "Выбранный вами статус может устанавливать только бухгалтер или директор. Пожалуйста, обратитесь к администратору.";
                    }
                }
            }
            echo json_encode(array("response" => $response, "message" => $message));
        }

        //Никакого окна подтверждения выведено не будет, карточка сохранится как обычно
        return;
    }
}
