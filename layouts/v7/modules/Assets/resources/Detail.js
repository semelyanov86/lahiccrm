/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Detail_Js("Assets_Detail_Js", {

    recalculate: function () {
        var self = this;
        var params = {};
        params['action'] = 'ActionAjax';
        params['module'] = app.getModuleName();
        params['mode'] = 'recalculate';
        params['record'] = app.getRecordId();
        app.helper.showProgress();
        app.request.post({data:params}).then(
            function (err,data) {
                if(err == null) {
                    app.helper.showSuccessNotification({message: data.message});
                } else {
                    app.helper.showErrorMessage('There was an error in calculation');
                }
                app.helper.hideProgress();
                window.location.reload(false);
            }
        );
    },

}, {

});