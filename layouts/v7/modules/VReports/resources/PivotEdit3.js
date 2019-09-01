VReports_Edit1_Js("VReports_PivotEdit3_Js",{
    registerFieldForChosen : function() {
        vtUtils.showSelect2ElementView(jQuery('#groupbyfield_rows'));
        vtUtils.showSelect2ElementView(jQuery('#groupbyfield_columns'));
        if(app.getViewName() == 'PivotDetail'){
            vtUtils.showSelect2ElementView(jQuery('#datafields'));
        }else{
            vtUtils.showSelect2ElementView(jQuery('#datafields-pivot'));
        }
    },
    initSelectValues : function() {
        var groupByFieldRows = jQuery('#groupbyfield_rows');
        var sortBy = jQuery('#sort_by');
        var groupByFieldColumns = jQuery('#groupbyfield_columns');
        var dataFields = jQuery('#datafields-pivot');
        if(app.getViewName() == 'PivotDetail'){
            dataFields = jQuery('#datafields');
        }
        var groupByFieldValueRows = jQuery('input[name=groupbyfield_rows]').val();
        var sortByVal = jQuery('input[name=sort_by]').val();
        var orderByVal = jQuery('input[name=order_by]').val();
        var groupByFieldValueColumns = jQuery('input[name=groupbyfield_columns]').val();
        var dataFieldsValue = jQuery('input[name=datafields]').val();

        var groupByHTML = jQuery('#groupbyfield_element').clone().html();
        var dataFieldsHTML = jQuery('#datafields_element').clone().html();

        groupByFieldRows.html(groupByHTML);
        groupByFieldColumns.html(groupByHTML);
        dataFields.html(dataFieldsHTML);

        if(orderByVal){
            $('select[name="order_by"]').val(orderByVal).select2()
        }

        if(groupByFieldValueRows) {
            groupByFieldValueRows = JSON.parse(groupByFieldValueRows);
            VReports_PivotEdit3_Js.updateSelectElement(groupByFieldValueRows,groupByFieldColumns);
        }
        if(groupByFieldValueColumns) {
            groupByFieldValueColumns = JSON.parse(groupByFieldValueColumns);
            VReports_PivotEdit3_Js.updateSelectElement(groupByFieldValueColumns,groupByFieldRows);
        }
        if(dataFieldsValue) {
            dataFieldsValue = JSON.parse(dataFieldsValue);

        }

        if(dataFieldsValue && dataFieldsValue[0] && groupByFieldValueRows && groupByFieldValueRows[0] && groupByFieldValueColumns && groupByFieldValueColumns[0]) {
            groupByFieldRows.attr('multiple', true).select2("val", groupByFieldValueRows);
            var option = '';
            var res = groupByFieldRows.find(':selected').toArray().map(item => item.text).join();
            $.each(groupByFieldValueRows, function (index, value) {
                option += '<option value='+value+'>'+res.split(",")[index]+'</option>';
            });
            groupByFieldColumns.attr('multiple', true).select2("val", groupByFieldValueColumns);
            dataFields.attr('multiple', true).select2("val", dataFieldsValue);
            var res2 = dataFields.find(':selected').toArray().map(item => item.text).join();
            $.each(dataFieldsValue, function (index, value) {
                option += '<option value='+value+'>'+res2.split(",")[index]+'</option>';
            });
            if(sortBy && sortByVal){
                sortBy.attr('multiple', true).html(option);
                sortBy.attr('multiple', true).select2("val", JSON.parse(sortByVal));
            }
        }else{
            groupByFieldRows.attr('multiple', true);
            groupByFieldColumns.attr('multiple', true);
            dataFields.attr('multiple', true);
            sortBy.attr('multiple', true);
        }

        var primaryModule = jQuery('input[name="primary_module"]').val();
        var inventoryModules = ['Invoice', 'Quotes', 'PurchaseOrder', 'SalesOrder'];
        var secodaryModules = jQuery('input[name="secondary_modules"]').val();
        var secondaryIsInventory = false;
        inventoryModules.forEach(function (entry) {
            if (secodaryModules.indexOf(entry) != -1) {
                secondaryIsInventory = true;
            }
        });
        if ((jQuery.inArray(primaryModule, inventoryModules) !== -1 || secondaryIsInventory)) {
            var reg = new RegExp(/vtiger_inventoryproductrel*/);
            if (dataFields.val() && reg.test(dataFields.val())) {
                if(app.getViewName() == 'PivotDetail'){
                    jQuery('#datafields option').not('[value^="vtiger_inventoryproductrel"]').remove();
                }else{
                    jQuery('#datafields-pivot option').not('[value^="vtiger_inventoryproductrel"]').remove();
                }
            } else {
                if(app.getViewName() == 'PivotDetail'){
                    jQuery('#datafields option[value^="vtiger_inventoryproductrel"]').remove();
                }else{
                    jQuery('#datafields-pivot option[value^="vtiger_inventoryproductrel"]').remove();
                }
            }
        }
    },
    updateSelectElement: function(valueOption, fieldRowColumn) {
        var groupByFieldElement = jQuery("#groupbyfield_element");
        var fieldRowColumnVal = fieldRowColumn.val();
        fieldRowColumn.html(groupByFieldElement.clone().html());
        for (var i in valueOption) {
            valueOption[i] = valueOption[i].replace(/\\/g, "\\\\\\\\");
            fieldRowColumn.find('option[value="' + valueOption[i] + '"]').remove();
        }
        if (fieldRowColumnVal && fieldRowColumnVal[0]) {
            fieldRowColumn.select2("val", fieldRowColumnVal);
        }
    }
},{
    initialize : function(container) {
        if(typeof container == 'undefined') {
            container = jQuery('#pivot_report_step3');
        }
        if(container.is('#pivot_report_step3')) {
            this.setContainer(container);
        } else {
            this.setContainer(jQuery('#pivot_report_step3'));
        }
    },

    calculateValues : function(){
        //handled advanced filters saved values.
        var advfilterlist = jQuery('#advanced_filter','#pivot_report_step2').val();// value from step2
        jQuery('#advanced_filter','#pivot_report_step3').val(advfilterlist);
    },
    registerUpdateSelectElementEventForRows: function() {
        jQuery("#groupbyfield_rows").on("change", function(e) {
            var valueOption = jQuery(e.currentTarget).val();
            var fieldsColumn = jQuery("#groupbyfield_columns");
            VReports_PivotEdit3_Js.updateSelectElement(valueOption, fieldsColumn);
        });
    },

    registerUpdateSelectElementEventForColumns: function() {
        jQuery("#groupbyfield_columns").on("change", function(e) {
            var valueOption = jQuery(e.currentTarget).val();
            var fieldsRow = jQuery("#groupbyfield_rows");
            VReports_PivotEdit3_Js.updateSelectElement(valueOption, fieldsRow);
        });
    },

    registerSubmitEvent : function() {
        var thisInstance = this;
        jQuery('#generateReport').on('click', function(e) {
            e.preventDefault();
            var groupByFieldRows = jQuery('#groupbyfield_rows').val();
            var groupByFieldColumns = jQuery('#groupbyfield_columns').val();
            var dataFields = jQuery('#datafields-pivot').val();
            var renameDataValue = {};
            var renameValues = $('.rename-field-translate').find('[name="rename_field"]');
            for(var i=0; i < renameValues.length; i++) {
                var renameVal = $(renameValues[i]).val();
                var renameSelected = $(renameValues[i]).attr('data-selected');
                var renameFieldSlect = renameSelected.split(':');
                if(renameSelected == 'count(*)'){
                    var renameField = 'record_count';
                }else{
                    var renameField = renameFieldSlect[2]+'_'+renameFieldSlect[5];
                }
                var renameLabel = $(renameValues[i]).attr('data-fieldlabel');
                renameDataValue[i]={fieldname : renameField.toLowerCase(),fieldlabel : renameLabel,translatedLabel:renameVal,renameSelected:renameSelected };
            }
            $('[name="renamedatavalue"]').val(JSON.stringify(renameDataValue));
            var form = thisInstance.getContainer();
            if(groupByFieldRows && groupByFieldColumns && dataFields) {
                vtUtils.hideValidationMessage(jQuery('#s2id_groupbyfield_rows'));
                vtUtils.hideValidationMessage(jQuery('#s2id_groupbyfield_columns'));
                vtUtils.hideValidationMessage(jQuery('#s2id_datafields'));
                form.find('[name="action"]').remove();
                form.submit();
            } else if(!groupByFieldRows){
                vtUtils.showValidationMessage(jQuery('#s2id_groupbyfield_rows'), app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'));
                e.preventDefault();
            }else if(!groupByFieldColumns){
                vtUtils.showValidationMessage(jQuery('#s2id_groupbyfield_columns'), app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'));
                e.preventDefault();
            }else if(!dataFields){
                vtUtils.showValidationMessage(jQuery('#s2id_datafields'), app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'));
                e.preventDefault();
            }
        });
        thisInstance.triggerRenameFieldsAction();

        jQuery('#generatePivot').on('click', function(e) {
            e.preventDefault();
            var groupByFieldRows = jQuery('#groupbyfield_rows').val();
            var groupByFieldColumns = jQuery('#groupbyfield_columns').val();
            var dataFields = jQuery('#datafields-pivot').val();
            //get value field rename
            var renameDataValue = {};
            var renameValues = $('.rename-field-translate').find('[name="rename_field"]');
            for(var i=0; i < renameValues.length; i++) {
                var renameVal = $(renameValues[i]).val();
                var renameSelected = $(renameValues[i]).attr('data-selected');
                var renameFieldSlect = renameSelected.split(':');
                if(renameSelected == 'count(*)'){
                    var renameField = 'record_count';
                }else{
                    var renameField = renameFieldSlect[2]+'_'+renameFieldSlect[5];
                }
                var renameLabel = $(renameValues[i]).attr('data-fieldlabel');
                renameDataValue[i]={fieldname : renameField.toLowerCase(),fieldlabel : renameLabel,translatedLabel:renameVal,renameSelected:renameSelected };
            }
            $('[name="renamedatavalue"]').val(JSON.stringify(renameDataValue));
            //end get value field rename
            var form = thisInstance.getContainer();
            if(groupByFieldRows && groupByFieldColumns && dataFields) {
                vtUtils.hideValidationMessage(jQuery('#s2id_groupbyfield_rows'));
                vtUtils.hideValidationMessage(jQuery('#s2id_groupbyfield_columns'));
                vtUtils.hideValidationMessage(jQuery('#s2id_datafields'));
                form.find('[name="view"],[name="mode"]').remove();
                form.submit();
            } else if(!groupByFieldRows){
                vtUtils.showValidationMessage(jQuery('#s2id_groupbyfield_rows'), app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'));
                e.preventDefault();
            }else if(!groupByFieldColumns){
                vtUtils.showValidationMessage(jQuery('#s2id_groupbyfield_columns'), app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'));
                e.preventDefault();
            }else if(!dataFields){
                vtUtils.showValidationMessage(jQuery('#s2id_datafields'), app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'));
                e.preventDefault();
            }
        });
    },
    submit : function(){
        var thisInstance = this;
        var aDeferred = jQuery.Deferred();
        thisInstance.calculateValues();
        var form = this.getContainer();
        var formData = form.serializeFormData();
        app.helper.showProgress();
        app.request.post({data:formData}).then(
            function(error,data) {
                if(form.find('[name="action"]').length > 0){
                    window.location.href = data;
                }else{
                    form.hide();
                    app.helper.hideProgress();
                    aDeferred.resolve(data);
                }
            },
            function(error,err){

            }
        );
        return aDeferred.promise();
    },
    registerEvents : function(){
        this._super();
        this.calculateValues();
        this.registerSubmitEvent();
        this.registerUpdateSelectElementEventForRows();
        this.registerUpdateSelectElementEventForColumns();
        VReports_PivotEdit3_Js.registerFieldForChosen();
        VReports_PivotEdit3_Js.initSelectValues();
    }
});