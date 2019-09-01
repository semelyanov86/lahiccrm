Vtiger.Class("VTEEmailMarketing_GlobalEditTemplates_Js", {},
{
    reloadToEditTemplateEmailMarketing : function () {
        var recordId = jQuery('[name="record"]').val();
        if(app.getModuleName() == 'EmailTemplates' && app.getViewName() == 'Edit' && recordId){
            var params = {
                module: 'VTEEmailMarketing',
                action: 'ActionAjax',
                mode: 'getUrlEditTemplatesEmailMarketing',
                "recordId": recordId,
            };
            AppConnector.request(params).then(
                function (data) {
                    if(data.success == true){
                        window.location.href = data.result;
                    }
                }
            )
        }
    },
    registerEvents : function () {
        this.reloadToEditTemplateEmailMarketing();
    }
});

jQuery(document).ready(function () {
    var instance = new VTEEmailMarketing_GlobalEditTemplates_Js();
    instance.registerEvents();
});