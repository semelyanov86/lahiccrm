<?php


//This is the access privilege file
$is_admin=false;

$current_user_roles='H4';

$current_user_parent_role_seq='H1::H2::H3::H6::H4';

$current_user_profiles=array(2,);

$profileGlobalPermission=array('1'=>1,'2'=>1,);

$profileTabsPermission=array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'13'=>0,'14'=>0,'15'=>0,'16'=>0,'18'=>0,'19'=>0,'20'=>0,'21'=>0,'22'=>0,'23'=>0,'24'=>0,'25'=>0,'26'=>0,'27'=>0,'30'=>1,'31'=>0,'32'=>0,'33'=>0,'34'=>0,'35'=>0,'36'=>0,'37'=>0,'38'=>0,'39'=>0,'40'=>0,'41'=>0,'42'=>0,'43'=>0,'44'=>0,'45'=>0,'46'=>0,'47'=>0,'48'=>0,'49'=>0,'50'=>0,'51'=>0,'52'=>0,'53'=>0,'54'=>0,'55'=>0,'56'=>0,'57'=>0,'58'=>0,'59'=>0,'60'=>0,'61'=>0,'62'=>0,'63'=>0,'64'=>0,'65'=>0,'66'=>0,'67'=>0,'68'=>0,'69'=>0,'70'=>0,'71'=>0,'72'=>0,'73'=>0,'74'=>0,'75'=>0,'76'=>0,'77'=>0,'78'=>0,'79'=>0,'80'=>0,'81'=>0,'82'=>0,'83'=>0,'84'=>0,'85'=>0,'86'=>0,'87'=>0,'28'=>0,);

$profileActionPermission=array(2=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,8=>0,10=>0,),4=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,8=>0,10=>0,),6=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,8=>0,10=>0,),7=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,8=>0,9=>0,10=>0,),8=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,6=>1,),9=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,),13=>array(0=>1,1=>1,2=>1,3=>0,4=>0,7=>1,5=>1,6=>1,8=>0,10=>0,),14=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,10=>0,),15=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),16=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),18=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,10=>0,),19=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,10=>1,),20=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,),21=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,),22=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,),23=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,),25=>array(0=>1,1=>1,2=>1,3=>0,4=>0,7=>1,6=>0,13=>0,),26=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),31=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,11=>1,12=>1,),32=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),35=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),39=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),41=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),42=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),43=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),47=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),48=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),49=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),51=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),55=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>1,6=>1,10=>1,),56=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),57=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,10=>0,),60=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,),67=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,8=>0,),68=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,8=>0,),79=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,8=>0,),81=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,8=>0,),82=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,8=>0,),83=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,8=>0,),85=>array(0=>0,1=>0,2=>0,3=>0,4=>0,7=>0,5=>0,6=>0,8=>0,),);

$current_user_groups=array(2,3,4,);

$subordinate_roles=array('H8',);

$parent_roles=array('H1','H2','H3','H6',);

$subordinate_roles_users=array('H8'=>array(13,14,15,20,21,23,27,28,29,31,32,34,),);

$user_info=array('user_name'=>'Susen','is_admin'=>'off','user_password'=>'$1$Su000000$9i.sE4OSQzpCHcZTFm1AU/','confirm_password'=>'$1$Su000000$9i.sE4OSQzpCHcZTFm1AU/','first_name'=>'S&uuml;sən','last_name'=>'Mustafayeva','roleid'=>'H4','email1'=>'s.mustafayeva@aquakristal.az','status'=>'Inactive','activity_view'=>'Today','lead_view'=>'Today','hour_format'=>'24','end_hour'=>'','start_hour'=>'09:00','is_owner'=>'','title'=>'','phone_work'=>'','department'=>'','phone_mobile'=>'','reports_to_id'=>'','phone_other'=>'','email2'=>'','phone_fax'=>'','secondaryemail'=>'','phone_home'=>'','date_format'=>'dd-mm-yyyy','signature'=>'','description'=>'','address_street'=>'','address_city'=>'','address_state'=>'','address_postalcode'=>'','address_country'=>'','accesskey'=>'5KiLmi1rjYyiREP','time_zone'=>'Asia/Baku','currency_id'=>'2','currency_grouping_pattern'=>'123456789','currency_decimal_separator'=>'.','currency_grouping_separator'=>',','currency_symbol_placement'=>'1.0$','imagename'=>'','internal_mailer'=>'0','theme'=>'softed','language'=>'ru_ru','reminder_interval'=>'','phone_crm_extension'=>'','sp_gravitel_id'=>'','sp_megafon_id'=>'','sp_zebra_login'=>'','sp_uiscom_id'=>'','sp_uiscom_extension'=>'','sp_telphin_extension'=>'','sp_zadarma_extension'=>'','sp_yandex_extension'=>'','sp_yandex_outgoing_number'=>'','sp_domru_id'=>'','sp_westcall_spb_id'=>'','sp_mcn_extension'=>'','sp_rostelecom_extension'=>'','sp_rostelecom_extension_internal'=>'','sp_rostelecom_extension_sipiru'=>'','sp_sipuni_extension'=>'','sp_mango_extension'=>'','no_of_currency_decimals'=>'2','truncate_trailing_zeros'=>'0','dayoftheweek'=>'Monday','callduration'=>'5','othereventduration'=>'5','calendarsharedtype'=>'public','default_record_view'=>'Summary','leftpanelhide'=>'0','rowheight'=>'medium','defaulteventstatus'=>'','defaultactivitytype'=>'','hidecompletedevents'=>'0','defaultcalendarview'=>'','currency_name'=>'Azerbaijan, New Manats','currency_code'=>'AZN','currency_symbol'=>'ман','conv_rate'=>'0.02700','record_id'=>'','record_module'=>'','id'=>'18');
?>