<?php
set_time_limit(0);

include "settings.php";
include "function.php";

require 'vendor/autoload.php';

use Salaros\Vtiger\VTWSCLib\WSClient;
$client = new WSClient($crm_url, $crm_login, $crm_key);

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Не удалось соединиться: ' . mysql_error());
mysqli_set_charset($link, "utf8");


$start=time();

while ((time()-$start)<60)
{
//Лиды с Bina
$sql="SELECT * FROM `bina_info` WHERE `status`=1 and `phone`<>'' LIMIT 0,1";
$result= mysqli_query($link, $sql);
while ($row= mysqli_fetch_array($result, 3))
{
    AddLeads ("bina_info",$row["link"],"https://bina.az",$row["phone"],$row["name"],$row["price"]);
}

//Лиды с Tap
$sql="SELECT * FROM `tap_info` WHERE `status`=1  and `phone`<>'' LIMIT 0,1";
$result= mysqli_query($link, $sql);
while ($row= mysqli_fetch_array($result, 3))
{
    AddLeads ("tap_info",$row["link"],"https://tap.az",$row["phone"],$row["name"],$row["price"]);
}
}


function AddLeads($table,$tbl_link,$url_pref,$phone_db,$name_db, $price_db)
{   
    global $link;
    global $client;
    
    $phone_db=substr($phone_db,0,strlen($phone_db)-1);
    $phones=explode(";",$phone_db);
    
    if (count($phones)>=3) {$ph_3=ValidPhone($phones[2]);} else {$ph_3='';}
    $ph_1=ValidPhone($phones[0]);
    echo $ph_1.'<br>';
    $checkLead = $client->entities->getNumericID('Leads', ['phone'=> $ph_1,]);
    echo $checkLead.'<br>';
    if (intval($checkLead) < 1) {
        $newLead = $client->entities->createOne('Leads', [
        'salutationtype'    => 'Mr.',
        'firstname'         => $name_db,
        'lastname'          => $name_db,
        'phone'             => $ph_1,
        'fax'               => $ph_3,
        'leadsource'        => "Parser",
        'website'           => $url_pref.$tbl_link,
        'leadstatus'        => "Cold",
        'annualrevenue'     => $price_db,
	//'assigned_user_id' => '2', 
	'assigned_user_id' => '29x2' 
            ]);
        $sql="UPDATE `".$table."` SET `status`='2' WHERE (`link`='".$tbl_link."')";
        echo $sql.'<br>';
        mysqli_query($link, $sql);
        InLog($table,"NewLead",$ph_1,$name_db);
        //exit;
        } else {
            $sql="UPDATE `".$table."` SET `status`='3' WHERE (`link`='".$tbl_link."')";
            echo $sql.'<br>';
            mysqli_query($link, $sql);
            InLog($table,"LeadDouble",$ph_1,$name_db);
            //exit;
            // Запись уже есть, значит факт пропуска записываем в логи
        }
}

function ValidPhone($tel)
{
    
    $tel=str_replace("(0","+994",$tel);
    $tel=str_replace(" ","",$tel);
    $tel=str_replace(")","",$tel);
    $tel=str_replace("-","",$tel);
    
    return ($tel);
}


?>
