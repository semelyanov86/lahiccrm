/*********************************************************************************
 * The content of this file is subject to the PDF Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

Vtiger.Class('PDFMaker_Uninstall_Js',{
    uninstallInstance: false,
        getInstance: function () {
            if (PDFMaker_Uninstall_Js.uninstallInstance == false) {
                var instance = new window["PDFMaker_Uninstall_Js"]();
                PDFMaker_Uninstall_Js.uninstallInstance = instance;
                return instance;
            }
            return PDFMaker_Uninstall_Js.uninstallInstance;
        }
    },{
        uninstallPDFMaker: function() {

            var message = app.vtranslate('LBL_UNINSTALL_CONFIRM','PDFMaker');
            app.helper.showConfirmationBox({'message': message}).then(function() {
                app.helper.showProgress();
                app.request.post({'url':'index.php?module=PDFMaker&action=UninstallPDFMaker'}).then(
                    function(err,response) {

                    app.helper.hideProgress();
                    if(err === null){
                        if (response.success == true) {
                            app.helper.showSuccessNotification({message: app.vtranslate('JS_ITEMS_DELETED_SUCCESSFULLY')});
                            window.location.href = "index.php";
                        } else {
                            app.helper.showErrorNotification({message: ''});
                        }
                    } else {
                        app.helper.showErrorNotification({message: err});
                    }
                });
            });
	    },
        registerEvents: function() {
            this.registerActions();
        },
        registerActions : function() {
            var thisInstance = this;
            jQuery('#uninstall_pdfmaker_btn').click(function(e) {
                thisInstance.uninstallPDFMaker();
            });
        }
});