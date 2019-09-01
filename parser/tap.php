<?php

include_once 'simple_html_dom.php';
include_once 'settings.php';
include_once 'function.php';
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Не удалось соединиться: ' . mysql_error());
mysqli_set_charset($link, "utf8");

$sql="SELECT * FROM `tap_links` WHERE `dt_check`<'".date("Y-m-d",time())."'";
$result= mysqli_query($link, $sql);

if (mysqli_num_rows($result)==0) {exit;}

$row= mysqli_fetch_array($result, 3);

$url=$row['link'];
//$url='https://tap.az/all/real-estate/apartments?utf8=%E2%9C%93&log=true&keywords=&q%5Bregion_id%5D=&order=price_desc&q%5Buser_id%5D=&q%5Bcontact_id%5D=&q%5Bprice%5D%5B%5D=&q%5Bprice%5D%5B%5D=&p%5B740%5D=&p%5B747%5D=&p%5B737%5D%5B%5D=&p%5B737%5D%5B%5D=&p%5B736%5D%5B%5D=&p%5B736%5D%5B%5D=';
$page=1;

while ($page<=20)
{
$url=GetPage($url);
$page++;

}
mysqli_query($link, "UPDATE `tap_links` SET `dt_check`='".date("Y-m-d",time())."' WHERE (`id`='".$row["id"]."')");




function GetPage($url)
{
    global $link;
    
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 YaBrowser/19.3.1.828 Yowser/2.5 Safari/537.36';
$headers[] = 'Referer: https://tap.az';
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
$products=$html->find('div.products-i');
foreach ($products as $product)
{
    $a_href=$product->find('a.products-link');
    $pr_n=$product->find('div.products-name');
    $pr_d=$product->find('div.products-created');
    
    $pr_pr=$product->find('span.price-val');
    
    
    if (strpos($pr_d[0]->plaintext,'dünən')!=false)
    {
    echo '<a href='.$a_href[0]->href.'>'.$pr_n[0]->plaintext.'</a>'.$pr_d[0]->plaintext.'<br>';
    $sql="INSERT INTO `tap_info` (`link`, `caption`, `price`) VALUES ('".$a_href[0]->href."', '".$pr_n[0]->plaintext."', '".str_replace(" ","",$pr_pr[0]->plaintext)."')";
    mysqli_query($link, $sql);
    InLog("tap.az","NewItem",$a_href[0]->href,"");
    }
}

$a_next=$html->find('div.next a');
echo 'https://tap.az'.$a_next[0]->href.'<hr>';
return ('https://tap.az'.$a_next[0]->href);
}


?>
