/* ********************************************************************************
 * The content of this file is subject to the Quoting Tool ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

/* Include on link table */

/** @class QuotingToolJS */
jQuery.Class('QuotingToolJS', {
    triggerShowModal: function () {
        var module = app.getModuleName();
        var progressIndicatorInstance = $.progressIndicator({});
        // Add Quoting Tool button
            var params = {
                'action': 'ActionAjax',
                'mode': 'getTemplate',
                'module': 'QuotingTool',
                'rel_module': module
            };
        AppConnector.request(params).then(
            function (response) {
                if (response) {
                    var templates = response.result;
                    if (templates.length > 0) {
                        var quotingTool = new QuotingToolJS();
                        quotingTool.showWidgetModal(templates)
                    }
                    progressIndicatorInstance.hide();
                }
            },
            function (error) {
                progressIndicatorInstance.hide();
                console.log('error =', error);
            }
        );
    }
}, {
    MODULE: 'QuotingTool',
    detailViewButtoncontainer: null,

    /**
     * Fn - getSelectedTemplates
     * @returns {*}
     */
    getSelectedTemplates: function () {
        var lstTemplates = jQuery('#lstTemplates');
        var selected = lstTemplates.val();

        if (selected == null || selected.length == 0) {
            alert(app.vtranslate('Please select template'));
            return null;
        }

        if (typeof selected !== 'Array') {
            return selected;
        }

        // When multi select
        var strSelected = '';
        for (var i = 0; i < selected.length; i++) {
            strSelected += selected[i] + '+';
        }

        strSelected = strSelected.substring(0, strSelected.length - 1);

        return strSelected;
    },

    /**
     * Function returns the record id
     */
    getRecordId: function () {
        var view = jQuery('[name="view"]').val();
        var recordId;
        if (view == "Edit") {
            recordId = jQuery('[name="record"]').val();
        } else if (view == "Detail") {
            recordId = jQuery('#recordId').val();
        }
        return recordId;
    },

    /**
     * Fn - registerWidgetActions
     */
    registerWidgetActions: function () {
        var thisInstance = this;
        var module = app.getModuleName();
        var recordId = thisInstance.getRecordId();

        // Export PDF
        jQuery(document).on('click', '[data-action="export"]', function () {
            var thisFocus = $(this);
            // Priority: 1. current button; 2. select box
            var templateId = thisFocus.data('template');

            if (!templateId) {
                templateId = thisInstance.getSelectedTemplates();
            }

            if (templateId) {
                if(app.getViewName() == 'List'){
                    var listInstance = window.app.controller();
                    var selectedRecordCount = listInstance.getSelectedRecordCount();
                    if (selectedRecordCount > 100) {
                        app.helper.showErrorNotification({message: app.vtranslate('JS_MASS_EDIT_LIMIT')});
                        return;
                    }
                    // var params = listInstance.getListSelectAllParams(true);
                    var checkedParams = JSON.stringify(listInstance.getListSelectAllParams(true));
                    var link = 'index.php?module=QuotingTool&action=PDFHandler&mode=listExport&relmodule='
                        + module + '&record=' + recordId + '&template_id=' + templateId + '&checked_params='+checkedParams;
                    document.location.href = link;
                }else{
                    document.location.href = 'index.php?module=QuotingTool&action=PDFHandler&mode=export&relmodule='
                        + module + '&record=' + recordId + '&template_id=' + templateId;

                }
            }
        });

        // Send email
        jQuery(document).on('click', '[data-action="send_email"]', function () {
            var thisFocus = $(this);
            // Priority: 1. current button; 2. select box
            var templateId = thisFocus.data('template');

            if (!templateId) {
                templateId = thisInstance.getSelectedTemplates();
            }

            if (templateId) {
                var progressIndicatorElement = jQuery.progressIndicator({
                    'position': 'html',
                    'blockInfo': {
                        'enabled': true
                    }
                });
                AppConnector.request('index.php?module=QuotingTool&view=SelectEmailFields&mode=send_email&relmodule='
                    + module + '&record=' + recordId + '&template_id=' + templateId).then(
                    function (data) {
                        progressIndicatorElement.progressIndicator({'mode': 'hide'});

                        if (data) {
                            var callBackFunction = function (data) {
                                var form = jQuery('#SendEmailFormStep1');
                                var params = app.validationEngineOptions;
                                params.onValidationComplete = function (form, valid) {
                                    if (valid) {
                                        app.hideModalWindow();
                                        var progressIndicatorElement = jQuery.progressIndicator({
                                            'message': 'Sending...',
                                            'position': 'html',
                                            'blockInfo': {
                                                'enabled': true
                                            }
                                        });
                                        var data = form.serializeFormData();
                                        AppConnector.request(data).then(
                                            function (response) {
                                                progressIndicatorElement.progressIndicator({'mode': 'hide'});

                                                if (response.success == true) {
                                                    Vtiger_Helper_Js.showMessage({
                                                        type: 'success',
                                                        text: response.result.message
                                                    });
                                                } else {
                                                    Vtiger_Helper_Js.showMessage({
                                                        type: 'error',
                                                        text: response.error.message
                                                    });
                                                }
                                            },
                                            function (error) {
                                                progressIndicatorElement.progressIndicator({'mode': 'hide'});
                                                //TODO : Handle error
                                                Vtiger_Helper_Js.showMessage({
                                                    type: 'error',
                                                    text: error
                                                });
                                            }
                                        );

                                        return valid;
                                    }
                                };
                                form.validationEngine(params);

                                form.submit(function (e) {
                                    e.preventDefault();
                                })
                            };
                            app.showModalWindow(data, function (data) {
                                if (typeof callBackFunction == 'function') {
                                    callBackFunction(data);
                                }
                            }, {'width': '350px'})
                        }
                    }
                );
            }
        });

        // Preview and send email
        jQuery(document).on('click', '[data-action="preview_and_send_email"]', function () {
            var thisFocus = $(this);
            // Priority: 1. current button; 2. select box
            var templateId = thisFocus.data('template');

            if (!templateId) {
                // get all selected templates
                templateId = thisInstance.getSelectedTemplates();
            }

            if (!templateId) {
                // Invalid template id
                return;
            }
            var iscreatenewrecord = thisFocus.data('iscreatenewrecord');
            var childModule = thisFocus.data('childmodule');

            if (templateId) {
                // Show indicator
                var progressIndicatorElement = jQuery.progressIndicator({
                    'position': 'html',
                    'blockInfo': {
                        'enabled': false
                    }
                });
                if(app.getViewName()=='List') {
                    var multiRecordId = [];
                    var checkBox = jQuery('input[class="listViewEntriesCheckBox"]:checked');
                    for (var i = 0; i < checkBox.length; i++) {
                        multiRecordId.push(checkBox[i].value);
                    }

                    var actionParams = {
                        'type': 'POST',
                        'url': 'index.php?module=QuotingTool&view=EmailPreviewTemplate&record=' + recordId + '&template_id=' + templateId,
                        'dataType': 'html',
                        'data': {
                            module: 'QuotingTool',
                            view: 'EmailPreviewTemplate',
                            isCreateNewRecord: iscreatenewrecord,
                            childModule: childModule,
                            multiRecord: multiRecordId,
                            // }
                        }
                    }

                }
                else {
                    var actionParams = {
                        "type": "POST",
                        "url": 'index.php?module=QuotingTool&view=EmailPreviewTemplate&record=' + recordId + '&template_id=' + templateId,
                        "dataType": "html",
                        "data": {
                            module: 'QuotingTool',
                            view: 'EmailPreviewTemplate',
                            isCreateNewRecord: iscreatenewrecord,
                            childModule: childModule,
                        }
                    };
                }

                AppConnector.request(actionParams).then(function (data) {
                    // Hide indicator
                    progressIndicatorElement.progressIndicator({'mode': 'hide'});

                    if (data) {
                        app.showModalWindow(data, function () {
                            thisInstance.registerEventForEmailPopup(progressIndicatorElement);
                        }, {'width': '796px'})
                    }
                });
            }
        });

        // Download PDF with signature
        jQuery(document).on('click', '[data-action="download_with_signature"]', function () {
            var templateId = thisInstance.getSelectedTemplates();

            if (templateId) {
                document.location.href = 'index.php?module=QuotingTool&action=PDFHandler&mode=download_with_signature&relmodule='
                    + module + '&record=' + recordId + '&template_id=' + templateId;
            }
        });
        
        // Download PDF with signature
        jQuery(document).on('click', '.btnChangeSignature', function () {
            if ($(this).hasClass("btn-primary")){
                return false;
            }
            var currentInstance = $(this);
            var transaction_id = $("[name='transaction_id']").val();
            var sign_to = $(this).data('sign-to');
            // Show indicator
            var progressIndicatorElement = jQuery.progressIndicator({
                'position': 'html',
                'blockInfo': {
                    'enabled': false
                }
            });
            var params = {};
            params['module'] = 'QuotingTool';
            params['action'] = 'ActionAjax';
            params['mode'] = 'changeSignTo';
            params['transaction_id'] = transaction_id;
            params['sign_to'] = sign_to;
            AppConnector.request({data: params}).then(function (data) {
                    // Hide indicator
                    progressIndicatorElement.progressIndicator({'mode': 'hide'});

                    if (data) {
                        $('.btnChangeSignature.btn-primary').addClass("btn-default");
                        $('.btnChangeSignature.btn-primary').removeClass("btn-primary");
                        currentInstance.removeClass("btn-default");
                        currentInstance.addClass("btn-primary");
                    }
                },
                function(error) {
                    progressIndicatorElement.progressIndicator({'mode': 'hide'});
                }
            );
        });

    },

    /**
     * Fn - registerEventForEmailPopup
     * @param progressIndicatorElement
     */
    registerEventForEmailPopup: function (progressIndicatorElement) {
        var thisInstance = this;
        thisInstance.registerEmailTags();

        var formEmail = jQuery('#quotingtool_emailtemplate');
        var inEmailSubject = formEmail.find('#email_subject');
        var inEmailContent = formEmail.find('#email_content');


        var editInstance = new Emails_MassEdit_Js();
        editInstance.registerEvents();

        var editorEmailContent = CKEDITOR.replace('email_content', {
            fullPage: true,
            toolbar: [
                {name: 'clipboard', items: ['Undo', 'Redo']},
                {name: 'tools', items: ['Source', 'Maximize', 'Preview']},
                {
                    name: 'editing',
                    groups: ['find', 'selection', 'spellchecker'],
                    items: ['Find', 'Replace', 'SelectAll', 'Scayt']
                },
                /*'/',*/
                {name: 'styles', items: ['Styles', 'Font', 'FontSize']},
                {name: 'colors', items: ['TextColor', 'BGColor']},
                '/',
                {name: 'insert', items: ['Image', 'Table']},
                {name: 'links', items: ['Link', 'Unlink']},
                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    //groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                    //items: ['Blockquote', 'CreateDiv', '-', 'BidiLtr', 'BidiRtl']
                    items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'JustifyLeft', 'JustifyCenter',
                        'JustifyRight', 'JustifyBlock', 'BidiLtr', 'BidiRtl']
                },
                {name: 'about', items: ['About']}
            ]
        });

        // When update email content
        formEmail.submit(function (event) {
            event.preventDefault();

            if (formEmail.find('.emailField:checked').length == 0 || formEmail.find('.emailField').length == 0) {
                Vtiger_Helper_Js.showMessage({
                    type: 'error',
                    text: 'Please select at least one email'
                });
                return false;
            }
            if (formEmail.find('[name="email_subject"]').val() == '') {
                Vtiger_Helper_Js.showMessage({
                    type: 'error',
                    text: app.vtranslate('Email Subject is required')
                });
                return false;
            }

            // Show indicator
            progressIndicatorElement.progressIndicator({
                'mode': 'show',
                'message': 'Sending...',
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });

            inEmailSubject.val(QuotingToolUtils.base64Encode(inEmailSubject.val()));
            inEmailContent.val(QuotingToolUtils.base64Encode(editorEmailContent.getData()));
            var formData = new FormData(formEmail[0]);
            var aDeferred = jQuery.Deferred();

            if(jQuery('input[name="multi_record"]').val() != undefined && jQuery('input[name="multi_record"]').val() != ''){
                formData.multi_record = JSON.parse(jQuery('input[name="multi_record"]').val());
            }

            if (formData) {
                var params = {
                    url: "index.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false
                };
                AppConnector.request(params).then(
                    function(data){
                        aDeferred.resolve(data);
                        if (data.success == true) {
                            Vtiger_Helper_Js.showMessage({
                                type: 'success',
                                text: data.result.message
                            });
                        } else {
                            Vtiger_Helper_Js.showMessage({
                                type: 'error',
                                text: data.error.message
                            });
                        }
                    },
                    function(textStatus, errorThrown){
                        aDeferred.reject(textStatus, errorThrown);
                    }).done(function () {
                    progressIndicatorElement.progressIndicator({'mode': 'hide'});
                    // Hide modal
                    app.hideModalWindow();
                });
            }

            /*AppConnector.request(data).then(
                function (response) {
                    if (response.success == true) {
                        Vtiger_Helper_Js.showMessage({
                            type: 'success',
                            text: response.result.message
                        });
                    } else {
                        Vtiger_Helper_Js.showMessage({
                            type: 'error',
                            text: response.error.message
                        });
                    }
                },
                function (error) {
                    console.log('error =', error);
                }
            ).done(function () {
                progressIndicatorElement.progressIndicator({'mode': 'hide'});
                // Hide modal
                app.hideModalWindow();
            });*/
        });
    },

    registerWidgetButtons: function () {
        var thisInstance = this;
        var module = app.getModuleName();
        var view = app.getViewName();
        var record = thisInstance.getRecordId();

        // Add Quoting Tool button
        if (view == "Detail" && record != undefined) {
            var progressIndicatorInstance = $.progressIndicator({});
            var actionParams = {
                "action": "ActionAjax",
                "mode": "getTemplate",
                "module": thisInstance.MODULE,
                "record": record,
                "rel_module": module
            };

            AppConnector.request(actionParams).then(
                function (response) {
                    if (response) {
                        var templates = response.result;
                        var button = jQuery('<span class="btn-group"><button class="btn btn-quoting_tool"><strong>Document Designer</strong></button></span>');

                        var themeSettings = QuotingToolUtils.getThemeSettings();
                        button.find('.btn-quoting_tool').css({
                            'background-color': themeSettings['background-color'],
                            'color': themeSettings['color']
                        });

                        if (templates.length > 0) {
                            var firstButton = thisInstance.detailViewButtoncontainer.find('.btn-toolbar > span:nth-child(1)"');
                            firstButton.before(button);
                            button.on('click', function () {
                                var params = {
                                    'action': 'ActionAjax',
                                    'mode': 'checkMPDF',
                                    'module': thisInstance.MODULE
                                };
                                AppConnector.request(params).then(
                                    function (response) {
                                        if (response) {
                                            if(response.result == 'success'){
                                                thisInstance.showWidgetModal(templates);
                                            }else{
                                                alert('MPDF Library is missing or needs to be upgraded. Please go to Document Designer template list/configuration to download MPDF library.');
                                                window.open(response.result, '_blank');
                                            }
                                        }
                                    }
                                );
                            });
                        }

                        progressIndicatorInstance.hide();

                    }
                },
                function (error) {
                    progressIndicatorInstance.hide();
                    console.log('error =', error);
                }
            );
        }
    },

    IconHelpText: function () {
        var module = app.getModuleName();
        var html = '<div class="modal modal2 fade" style="display: none;" aria-hidden="false" id="modal2" data-backdrop="static" data-keyboard="false">'
            + '<div class="modal-dialog modal-lg">'
            + '<div class="modal-content">'
            + '<div class="modal-header">'
            + '<div class="clearfix">'
            + '<div class="pull-right">'
            + '<button type="button" class="close" aria-label="Close" id="btnModal2">'
            + '<span aria-hidden="true" class="fa fa-close"></span>'
            + '</button>'
            + '</div>'

            + '</div>'
            + '</div>'
            + '<div class="modal-body" style="overflow-y: auto;">';

        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>';
        // Append Modal2 to Body of Website
        jQuery("body").append(html);


        //Get Data From Input
        if(module=='QuotingTool' && app.getViewName() == 'Edit') {
            var dataHelptext = jQuery('input[name="icon_helptext"]').val();
            if(dataHelptext != '' && dataHelptext != undefined){
                dataHelptext = JSON.parse(dataHelptext);
            }
        }
        //Click Modal Icon_HelpText
        jQuery(document).on('click','.icon-helptext',function(e) {
            var id_helptext = jQuery(this).attr("id");

            for(var i=0;i<dataHelptext.length;i++){
                if(dataHelptext[i].element==id_helptext){
                    templates = dataHelptext[i].helptext;
                    break;
                }
                else if(id_helptext=="create_new_record"){
                    templates="You can send forms to external people as well as existing customers. Filled out forms will be created as new records. Please refer to the user guide for instructions";
                    break;
                }
                else{
                    templates = "No data";
                }
            }

            jQuery(".modal2 .modal-body").append('<h5 id="templates">'+templates+'</h5>');
            jQuery("#modal2").modal('show');

        });


    },
    /**
     * Fn - showWidgetModal
     * @param templates
     */
    showWidgetModal: function (templates) {
        var html = '<div id="modalQuotingToolWidget" class="modal-quotingtool-widget">'
            + '<div class="modal-header">'
            + '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
            + '<span aria-hidden="true">&times;</span>'
            + '</button>'
            + '<h4 class="modal-title" id="myModalLabel">Document Designer (Email/PDF)</h4>'
            + '</div>'
            + '<div class="modal-body">'
            + '<form method="post" action="">'
            + '<table id="tableQuotingToolWidget">'
            + '<thead>'
            + '<th>Template Name</th>'
            + '<th class="actions">PDF</th>'
            + '<th class="actions">Email</th>'
            + '</thead>'
            + '<tbody>';
        var template = null;
        if (templates && Array.isArray(templates)) {
            var currentModule  = app.getModuleName();
            var isCreateNewRecord = '';
            for (var i = 0; i < templates.length; i++) {
                template = templates[i];
                var moduleTemplate = template.modulename;
                if(template.createnewrecords == 1 && currentModule != moduleTemplate) {
                     isCreateNewRecord = 1;
                }else{
                    isCreateNewRecord = 0;
                }
                html += '<tr>' +
                    '<td>' + template.filename + '</td>' +
                    '<td><a href="javascript:;" data-action="export" data-template="' + template.id + '">' +
                    '<img src="layouts/vlayout/modules/QuotingTool/resources/img/icons/widget-pdf.png" /></a></td>' +
                    '<td><a href="javascript:;" data-action="preview_and_send_email" data-template="' + template.id + '" data-childmodule = "' + moduleTemplate + '" data-iscreatenewrecord = "' + isCreateNewRecord + '">' +
                    '<img src="layouts/vlayout/modules/QuotingTool/resources/img/icons/widget-mail.png" /></td>' +
                    '</tr>';
            }
        } else {
            html += templates;
        }

        html += '</tbody>'
            + '</table>'
            + '</form>'
            + '</div>'
            + '</div>';

        app.showModalWindow(html, '#', function (data) {
        }, {'width': '600px'});
    },

    registerEmailTags: function () {
        var selectTags = jQuery('.select2-tags');

        selectTags.each(function () {
            var focus = jQuery(this);
            var tags = focus.data('tags');
            if (typeof tags === 'undefined' || !tags) {
                tags = [];
            }
            var select2params = {tags: tags/*, tokenSeparators: [","]*/};
            app.showSelect2ElementView(focus, select2params);
        });
    },

    registerEvents: function () {
        var thisInstance = this;
        thisInstance.registerWidgetActions();
        thisInstance.registerWidgetButtons();
        thisInstance.IconHelpText();
    }
});

jQuery(document).ready(function () {
    var module = app.getModuleName();
    // Fix auto add resizeable to textarea on IE
    if (jQuery.isFunction(jQuery.fn.resizable)) {
        jQuery('#quoting_tool-body').find("textarea.hide")
            .resizable('destroy')
            .removeAttr('style');
    }

    var instance = new QuotingToolJS();
    instance.detailViewButtoncontainer = jQuery('.detailViewButtoncontainer');
    instance.registerEvents();

    // Icon_HelpText
    if(module=='QuotingTool' && app.getViewName() == 'Edit') {
        var dataHelptext = jQuery('input[name="icon_helptext"]').val();
        if(dataHelptext != '' && dataHelptext != undefined){
            dataHelptext = JSON.parse(dataHelptext);
            if (dataHelptext.length > 0) {

                setInterval(function () {
                    jQuery("span.icon-helptext").removeClass("hide");
                    //clearInterval();
                }, 1000);
            }
        }

        jQuery("#btnModal2").on('click', function () {
            var element = document.getElementById("templates");
            element.parentNode.removeChild(element);
            jQuery(".modal2.in").modal('hide');
        });
        // End of Icon_HelpText
    }
});

jQuery(document).ajaxComplete( function (event, request, settings) {
    var url = settings.data;
    if (url && url.constructor.name=='String' && url.indexOf('module=QuotingToo') > -1 && url.indexOf('view=EmailPreviewTemplate') > -1){
        setTimeout(function () {
            var formEmail = jQuery(document).find('#quotingtool_emailtemplate');
            var emailInstance = new Emails_MassEdit_Js();
            formEmail.find('#browseCrm').on('click',function(e){
                var selectedDocumentId;
                var url = jQuery(e.currentTarget).data('url');
                var popupInstance = Vtiger_Popup_Js.getInstance();
                popupInstance.show(url,function(data){
                    var responseData = JSON.parse(data);
                    for(var id in responseData){
                        selectedDocumentId = id;
                        var selectedFileName = responseData[id].info['filename'];
                        var selectedFileSize =  responseData[id].info['filesize'];
                        var response = emailInstance.writeDocumentIds(selectedDocumentId);
                        if(response){
                            var attachmentElement = emailInstance.getDocumentAttachmentElement(selectedFileName,id,selectedFileSize);
                            //TODO handle the validation if the size exceeds 5mb before appending.
                            jQuery(attachmentElement).appendTo(jQuery('#attachments'));
                            jQuery('.MultiFile-applied,.MultiFile').addClass('removeNoFileChosen');
                            emailInstance.setDocumentsFileSize(selectedFileSize);
                        }
                    }

                },'browseCrmWindow');
            });
            // formEmail.find("#multiFile").MultiFile({
            //     list: '#attachments',
            //     'afterFileSelect' : function(element, value, master_element){
            //         var masterElement = master_element;
            //         var newElement = jQuery(masterElement.current);
            //         newElement.addClass('removeNoFileChosen');
            //         emailInstance.fileAfterSelectHandler(element, value, master_element);
            //     },
            //     'afterFileRemove' : function(element, value, master_element){
            //         if (jQuery('#attachments').is(':empty')){
            //             jQuery('.MultiFile,.MultiFile-applied').removeClass('removeNoFileChosen');
            //         }
            //         emailInstance.removeAttachmentFileSizeByElement(jQuery(element));
            //     }
            // });
            formEmail.on('click','#ccLink',function(e){
                jQuery('.ccContainer').removeClass('hide');
                jQuery(e.currentTarget).hide();
            });
            formEmail.on('click','#bccLink', function(e){
                jQuery('.bccContainer').removeClass('hide');
                jQuery(e.currentTarget).hide();
            });
        },500);

    }
});
