<?php
require_once 'include/utils/utils.php';
require_once 'include/utils/VtlibUtils.php';
require_once 'modules/Vtiger/helpers/ShortURL.php';
global $adb;
$adb = PearDatabase::getInstance();
$options = array(
    'handler_path' => 'modules/Leads/handlers/RemoveDublicates.php',
    'handler_class' => 'Leads_RemoveDublicates_Handler',
    'handler_function' => 'removeDublicates',
    'handler_data' => array()
);
$trackURL = Vtiger_ShortURL_Helper::generateURL($options);
var_dump($trackURL); // http://crm1.lahicsu.az:6931//shorturl.php?id=5d1e179d61d104.38497101