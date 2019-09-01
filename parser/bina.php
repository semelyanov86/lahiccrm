<?php
//set_time_limit(0);
include_once 'simple_html_dom.php';
include_once 'settings.php';
include_once 'function.php';

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Не удалось соединиться: ' . mysql_error());
mysqli_set_charset($link, "utf8");



$row= mysqli_fetch_array($result, 3);


$page=1;
$stop=false;
while ($stop==false)
{
$url='https://bina.az/items/all?page='.$page.'&sorting=bumped_at+desc';    
GetPage($url);
$page++;

}




function GetPage($url)
{
    global $link; 
    global $stop;
    echo $url.'<br>';
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 YaBrowser/19.3.1.828 Yowser/2.5 Safari/537.36';
$headers[] = 'Referer: https://bina.az';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

if ($proxy!='')
{
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    if ($proxyauth!='') {curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);}
}

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

//echo $result;

$html = str_get_html($result);
$products=$html->find('div.items-i');

$sv=0;
foreach ($products as $product)
{
    $a_href=$product->find('a.item_link');
    $pr_n=$product->find('div.card_params');
    $pr_d=$product->find('div.city_when');
    
    $pr_pr=$product->find('span.price-val');
    
    if (strpos($pr_d[0]->plaintext,'dünən')!=false or strpos($pr_d[0]->plaintext,'bugün')!=false)
    {
    $sv=$sv+1;
    echo '<a href='.$a_href[0]->href.'>'.$pr_n[0]->plaintext.'</a>'.$pr_d[0]->plaintext.'<br>';
    $sql="INSERT INTO `bina_info` (`link`, `caption`, `price`) VALUES ('".$a_href[0]->href."', '".$pr_n[0]->plaintext."', '".str_replace(" ","",$pr_pr[0]->plaintext)."')";
    mysqli_query($link, $sql);
    InLog("bina.az","NewItem",$a_href[0]->href,"");
    
    }
}

if ($sv>2) {$stop=false;} else {$stop=true;}
}


?>
