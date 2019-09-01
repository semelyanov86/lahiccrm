/* ********************************************************************************
 * The content of this file is subject to the Quoting Tool ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

(function () {
    'use strict';

    var utils = angular.module('AppUtils', []);

    utils.factory('AppUtils', function ($rootScope, $sce, $templateRequest, $compile, $translate, GlobalConfig, AppConstants) {
        return {

            /**
             * Fn - pasteHtmlAtCaret
             *
             * @link http://stackoverflow.com/questions/6690752/insert-html-at-caret-in-a-contenteditable-div
             * @param {String} text
             * @param {Object=} info
             * @param {Function=} callback
             */
            pasteHtmlAtCaret: function (text, info, callback) {
                if (!info) {
                    info = {};
                }
                var thisFocus = $rootScope.app.last_focus_item;

                if (!thisFocus) {
                    // Not found focus object
                    AppHelper.showMessage($translate.instant('Place cursor to insert'));
                    return;
                }

                var type = thisFocus['type'];
                var focus = thisFocus['focus'];

                switch (type) {
                    case AppConstants.FOCUS_TYPE.INPUT:
                    case AppConstants.FOCUS_TYPE.TEXTAREA:
                    case AppConstants.FOCUS_TYPE.CONTENTEDITABLE:
                        var dataInfo = focus.data('info');
                        if (!dataInfo) {
                            dataInfo = {};
                        }

                        $.extend(dataInfo, info);
                        focus.attr('data-info', JSON.stringify(dataInfo));
                        // comment this line because prevent change random id of block component :(
						// focus.attr('id', 'text_field_'+info['id']);
                        focus.attr('title', info['label']);

                        var focusId = focus.attr('id');
                        if (focusId && CKEDITOR.instances[focusId]) {
                            // If CKEditor instance exist
                            focus = CKEDITOR.instances[focusId];
                            text+=' ';
                            focus.insertHtml(text,'text');
                        } else {
                            focus.insertAtCaret(text);
                        }

                        break;
                    case AppConstants.FOCUS_TYPE.CKEDITOR:
                        text+='&nbsp;';
                        focus.insertHtml(text,'text');
                        break;
                    default:
                        break;
                }

                if (typeof callback === 'function') {
                    callback(focus);
                }
            },

            pasteCustomFunctionAtCaret: function (text, index, addto, info, callback) {
                if (!info) {
                    info = {};
                }
                index=parseInt(index);
                //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 - START
                //NOTES: addto = 'start' or for add new condition, this text append
                if(!addto || addto != 'end'){
                    text="<span class='custom_fucntion_css custom_function_"+index+"'>#CF_"+(index+1)+"_START#</span><br class='custom_function_"+index+"'>"+ text;
                }
                //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 -END
                var thisFocus = $rootScope.app.last_focus_item;

                if (!thisFocus) {
                    // Not found focus object
                    AppHelper.showMessage($translate.instant('Place cursor to insert'));
                    return false;
                }

                var type = thisFocus['type'];
                var focus = thisFocus['focus'];

                switch (type) {
                    case AppConstants.FOCUS_TYPE.INPUT:
                    case AppConstants.FOCUS_TYPE.TEXTAREA:
                    case AppConstants.FOCUS_TYPE.CONTENTEDITABLE:
                        var dataInfo = focus.data('info');
                        if (!dataInfo) {
                            dataInfo = {};
                        }

                        $.extend(dataInfo, info);
                        focus.attr('data-info', JSON.stringify(dataInfo));
                        // comment this line because prevent change random id of block component :(
                        // focus.attr('id', 'text_field_'+info['id']);
                        focus.attr('title', info['label']);

                        var focusId = focus.attr('id');
                        if (focusId && CKEDITOR.instances[focusId]) {
                            // If CKEditor instance exist
                            focus = CKEDITOR.instances[focusId];
                            if(!addto || addto == 'end'){
                                //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 - START
                                //NOTES: addto = 'end' or for add new condition, this text append
                                text+="<br class='custom_function_"+index+"'><span class='custom_fucntion_css custom_function_"+index+"'>#CF_"+(index+1)+"_END#</span>";
                                //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 -END
                            }
                            focus.insertHtml(text);
                        } else {
                            focus.insertAtCaret(text);
                        }

                        break;
                    case AppConstants.FOCUS_TYPE.CKEDITOR:
                        if(!addto || addto == 'end'){
                            //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 - START
                            //NOTES:
                            // addto = 'end' or for add new condition, this text append
                            text+="<br class='custom_function_"+index+"'><span class='custom_fucntion_css custom_function_"+index+"'>#CF_"+(index+1)+"_END#</span>";
                            //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 -END
                        }
                        focus.insertHtml(text);
                        break;
                    default:
                        break;
                }
                //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 - START
                //NOTES: add color and font size for text condition
                $('.custom_fucntion_css').css({
                    'font-size':'10px',
                    'color':'red'
                });
                //TASKID:12912 - DEV: tiennguyen - DATE: 2018-10-08 -END


                if (typeof callback === 'function') {
                    callback(focus);
                }
                return true;
            },

            /**
             * Fn - loadTemplate
             *
             * @param $scope
             * @param {String} path
             * @param {boolean} compile
             * @param {Function=} callback - callback(jQuery)
             */
            loadTemplate: function ($scope, path, compile, callback) {
                /**
                 * Make sure that no bad URLs are fetched. You can omit this if your template URL is not dynamic.
                 * @link http://stackoverflow.com/questions/24496201/load-html-template-from-file-into-a-variable-in-angularjs
                 */
                var templateUrl = $sce.getTrustedResourceUrl(path);

                // template is the HTML template as a string
                // Let's put it into an HTML element and parse any directives and expressions
                // in the code. (Note: This is just an example, modifying the DOM from within
                // a controller is considered bad style.)
                // Append template to DOM
                $templateRequest(templateUrl).then(function (template) {
                    var html = $(template);

                    // Compile Angular
                    if (compile) {
                        /**
                         * Bind event for template
                         * @link http://stackoverflow.com/questions/18618069/angularjs-event-binding-in-directive-template-doesnt-work-if-mouseout-used-but
                         */
                        $compile(html.contents())($scope);
                    }

                    // Callback
                    /** @link http://stackoverflow.com/questions/5999998/how-can-i-check-if-a-javascript-variable-is-function-type */
                    if (typeof callback === 'function') {
                        callback(html);
                    }
                }, function () {
                    // An error has occurred
                    alert($translate.instant('Load template error'));
                });
            }

        };
    });

})();