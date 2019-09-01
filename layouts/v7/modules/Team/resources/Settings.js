/* ********************************************************************************
 * The content of this file is subject to the Global Search ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

Vtiger_Index_Js("Team_Settings_Js",{

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
                errorMsg = "License Key cannot be empty";
                license_key.validationEngine('showPrompt', errorMsg , 'error','bottomLeft',true);
                aDeferred.reject();
                return aDeferred.promise();
            }else{
                app.helper.showProgress();
                var params = {};
                params['module'] = app.getModuleName();
                params['action'] = 'Activate';
                params['mode'] = 'activate';
                params['license'] = license_key.val();

                app.request.post({'data' : params}).then(
                    function(err,data){
                        if(err === null) {
                            app.helper.hideProgress();
                            if(data) {
                                var message=data.message;
                                if(message !='Valid License') {
                                    jQuery('#error_message').html(message);
                                    jQuery('#error_message').show();
                                }else{
                                    document.location.href="index.php?module=Team&parent=Settings&view=Settings&mode=step3";
                                }
                            }
                        }else{
                            app.helper.hideProgress();
                        }
                    }
                );
            }
        });
    },

    registerValidEvent: function () {
        jQuery(".installationContents").find('[name="btnFinish"]').click(function() {
            app.helper.showProgress();
            var params = {};
            params['module'] = app.getModuleName();
            params['action'] = 'Activate';
            params['mode'] = 'valid';

            app.request.post({'data' : params}).then(
                function(err,data){
                    if(err === null) {
                        app.helper.hideProgress();
                        if (data) {
                            document.location.href = "index.php?module=Team&parent=Settings&view=Settings";
                        }
                    }else{
                        app.helper.hideProgress();
                    }
                }
            );
        });
    },
    /* For License page - End */

    updatedBlockSequence : {},
    registerSelectModuleChange:function() {
        jQuery("#team_settings").on("change","#available_module", function(e) {
             app.helper.showProgress();
            var selectedModule=jQuery(this).val();
            var customField=jQuery("#team_settings").find("#customField");
            if(selectedModule !='') {
                var params= {
                    "type": "POST",
                    "url": "index.php?module=Team&parent=Settings&view=SettingsAjax",
                    "dataType":"html",
                    "data" : {
                        'selected_module':selectedModule
                    }
                };
                app.request.post(params).then(
                    function(err,data){
                        if(err === null) {
                            app.helper.hideProgress();
                            customField.html(data);
                            vtUtils.applyFieldElementsView(customField);
                        }
                    }
                )
            }else {
                app.helper.hideProgress();
                customField.html('');
            }
        });
    },

    registerSaveSettings:function() {
        jQuery("#team_settings").on("click",".btnSaveSettings", function(e) {
            app.helper.showProgress();
            var form=jQuery("#team_settings").find("#Settings");
            var saveUrl = form.serializeFormData();
            app.request.post({'data' : saveUrl}).then(
                function(err,data){
                    if(err === null) {
                        app.helper.hideProgress();
                    }
                }
            );
        });
    },
    registerSelectFieldTypeEvent: function() {
        jQuery("#team_settings").on("change","#fieldType", function(e) {
            var fieldType = jQuery(this).val();
            if(fieldType == 2){
                jQuery("#fieldcustom").prop('disabled',false);
            }else{
                jQuery("#fieldcustom").prop('disabled',true);
            }
        });

    },

    registerCronjobEvent : function () {
        jQuery("#cron_settings").on("change","#enable", function(e) {
            app.helper.showProgress();
            var element=e.currentTarget;
            var enable=0;
            var text="Cronjob Disabled";
            if(element.checked) {
                enable=1;
                text = "Cronjob Enabled";
            }
            var params = {};
            params['action']= 'ActionAjax';
            params['module'] = 'Team';
            params['mode'] = 'enableCronjob';
            params['enable'] = enable;

            app.request.post({data:params}).then(
                function (err,data) {
                    if (err == null) {
                        app.helper.hideProgress();
                        var params = {};
                        params['title'] = text;
                        Vtiger_Helper_Js.showMessage(params);
                    }
                }
            );
        });
    },

    /**
     * Function which will handle the registrations for the elements
     */
    registerEvents : function() {
        this._super();
        this.registerSelectModuleChange();
        this.registerSaveSettings();
        this.registerSelectFieldTypeEvent();
        this.registerCronjobEvent();
        /* For License page - Begin */
        this.registerActivateLicenseEvent();
        this.registerValidEvent();
        /* For License page - End */
    }
});

jQuery(document).ready(function() {
    var instance = new Team_Settings_Js();
    instance.registerEvents();
});