/* ********************************************************************************
 * The content of this file is subject to the Google Radius Maps ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
jQuery.Class("GoogleRadiusMaps_Settings_Js", {
    instance:false,
    getInstance: function(){
        if(GoogleRadiusMaps_Settings_Js.instance == false){
            var instance = new GoogleRadiusMaps_Settings_Js();
            GoogleRadiusMaps_Settings_Js.instance = instance;
            return instance;
        }
        return GoogleRadiusMaps_Settings_Js.instance;
    }
},{
    /* For License page - Begin */
    init : function() {
        this.initiate();
    },
    /*
     * Function to initiate the step 1 instance
     */
    initiate : function(){
        var step=jQuery(".installationContents").find('.step').val();
        this.initiateStep(step);
    },
    /*
     * Function to initiate all the operations for a step
     * @params step value
     */
    initiateStep : function(stepVal) {
        var step = 'step'+stepVal;
        this.activateHeader(step);
    },

    activateHeader : function(step) {
        var headersContainer = jQuery('.crumbs ');
        headersContainer.find('.active').removeClass('active');
        jQuery('#'+step,headersContainer).addClass('active');
    },
    registerActivateLicenseEvent : function() {
        var aDeferred = jQuery.Deferred();
        jQuery(".installationContents").find('[name="btnActivate"]').click(function() {
            var license_key=jQuery('#license_key');
            if(license_key.val()=='') {
                app.helper.showAlertBox({message:"License Key cannot be empty"});
                aDeferred.reject();
                return aDeferred.promise();
            }else{
                app.helper.showProgress();
                var params = {};
                params['module'] = app.getModuleName();
                params['action'] = 'Activate';
                params['mode'] = 'activate';
                params['license'] = license_key.val();

                app.request.post({data:params}).then(
                    function(err,data) {
                        app.helper.hideProgress();
                        if(err == null){
                            var message=data['message'];
                            if(message !='Valid License') {
                                app.helper.hideProgress();
                                app.helper.hideModal();
                                app.helper.showAlertNotification({'message':data['message']});
                            }else{
                                document.location.href="index.php?module=GoogleRadiusMaps&parent=Settings&view=Settings&mode=step3";
                            }
                        }
                    },
                    function(error) {
                        app.helper.hideProgress();
                    }
                );
            }
        });
    },
    registerValidEvent: function () {
        jQuery(".installationContents").find('[name="btnFinish"]').click(function() {
            app.helper.showProgress();
            var data = {};
            data['module'] = app.getModuleName();
            data['action'] = 'Activate';
            data['mode'] = 'valid';
            app.request.post({data:data}).then(
                function (err,data) {
                    if(err == null){
                        app.helper.hideProgress();
                        if (data) {
                            document.location.href = "index.php?module=GoogleRadiusMaps&parent=Settings&view=Settings";
                        }
                    }
                }
            );
        });
    },
    /* For License page - End */
    registerEnableModuleEvent:function() {
        jQuery('.summaryWidgetContainer').find('#btnGRMSettingSave').click(function() {
            var progressIndicatorElement = jQuery.progressIndicator({
                'position' : 'html',
                'blockInfo' : {
                    'enabled' : true
                }
            });

            var enable=0;
            var text="Google Radius Maps Disabled";
            if(jQuery("#enable_module").prop("checked")) {
                enable=1;
                text = "Google Radius Maps Enabled";
            }
            var is_default_view=0;
            if(jQuery("#is_default_view").prop("checked")) {
                is_default_view=1;
            }
            var params = {};
            params.action = 'ActionAjax';
            params.module = 'GoogleRadiusMaps';
            params.enable = enable;
            params.is_default_view = is_default_view;
            params.map_center = jQuery("#slbMapCenter").val();
            params.radius_unit = jQuery("#slbRadiusUnit").val();
            params.radius_number = jQuery("#txtRadiusNumber").val();
            params.s = 'enableModule';
            var enable_on = jQuery("#chkEnableOn").val();
            if (enable_on && enable_on.length > 0){
                enable_on = enable_on.join(',');
            } else {
                enable_on = '';
            }
            params.enable_on = enable_on;
            app.request.post({data:params}).then(
                function (errs, res) {
                    if (errs === null){
                        progressIndicatorElement.progressIndicator({'mode' : 'hide'});
                        var params = {};
                        params['text'] = text;
                        Settings_Vtiger_Index_Js.showMessage(params);
                    }
                }
                // function(data){
                //     progressIndicatorElement.progressIndicator({'mode' : 'hide'});
                //     var params = {};
                //     params['text'] = text;
                //     Settings_Vtiger_Index_Js.showMessage(params);
                // },
                // function(error){
                //     //TODO : Handle error
                //     progressIndicatorElement.progressIndicator({'mode' : 'hide'});
                // }
            );
        });
    },
    registerEvents: function(){
        this.registerEnableModuleEvent();
        /* For License page - Begin */
        this.registerActivateLicenseEvent();
        this.registerValidEvent();
        /* For License page - End */
    }
});

jQuery(document).ready(function() {
    var instance = new GoogleRadiusMaps_Settings_Js();
    instance.registerEvents();
    Vtiger_Index_Js.getInstance().registerEvents();
    $(".grm-tooltip").tooltip({
        html: true,
        position: {
            my: "center right", // the "anchor point" in the tooltip element
            at: "center right" // the position of that anchor point relative to selected element
        }
    });
});
