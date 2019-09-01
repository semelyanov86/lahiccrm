/* ********************************************************************************
 * The content of this file is subject to the Global Search ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

Vtiger.Class("Team_Js",{
    instance:false,
    getInstance: function(){
        if(Team_Js.instance == false){
            var instance = new Team_Js();
            Team_Js.instance = instance;
            return instance;
        }
        return Team_Js.instance;
    }
},{
    registerEventForEditButtons : function(form) {
        var thisInstance = this;
        var params = {};
        params['action']= 'ActionAjax';
        params['module'] = 'Team';
        params['mode'] = 'checkEnable';
        params['parentModule'] = app.getModuleName();

        app.request.post({data:params}).then(
            function (err,data) {
                if(err == null) {
                    if (data.enable == '1') {
                        if (app.view() == 'Detail') {
                            var editSumaryTeam = $('input').filter('[data-name="cf_team"]');
                            if(editSumaryTeam !== 'undefined') {
                                var editSumarySpan =editSumaryTeam.parent();
                                editSumarySpan.next().css("display", "none");
                            }
                            var buttonContainer = jQuery('#'+app.getModuleName()+'_detailView_fieldValue_cf_team');
                            var btnToolBar = buttonContainer.find('.editAction');
                            btnToolBar.css("display", "none");
                        }
                    }
                }
            }
        );
    },
    registerEvents : function() {
        this._super();
        this.registerEventForEditButtons();
    }
});

jQuery(document).ready(function() {
    // Only load when loadHeaderScript=1 BEGIN #241208
    if (typeof VTECheckLoadHeaderScript == 'function') {
        if (!VTECheckLoadHeaderScript('Team')) {
            return;
        }
    }
    // Only load when loadHeaderScript=1 END #241208

    // Only load when view is Detail or Edit
    if(app.view()!='Detail' && app.view()!='Edit') return;
    var instance = new Team_Js();
    instance.registerEvents();
});
jQuery(document). ajaxComplete(function (event, request, settings) {
    if (settings.url && (settings.url.indexOf('requestMode=summary') > -1 || settings.url.indexOf('requestMode=full') > -1)){
        var instance = new Team_Js();
        instance.registerEvents();
    }
});