<?php

include_once 'simple_html_dom.php';
include_once 'settings.php';
include_once 'function.php';

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Не удалось соединиться: ' . mysqli_error());
mysqli_set_charset($link, "utf8");

$start=time();

while ((time()-$start)<60)
{
    $sql="SELECT * FROM `bina_info` WHERE `status`=0 LIMIT 0,1";
    //echo $sql;
    $result= mysqli_query($link, $sql);
    if (mysqli_num_rows($result)==0) {exit;}
    while ($row= mysqli_fetch_array($result, 3))
    {
        $url='https://bina.az'.$row["link"];
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
        
        $NAM=''; $TEL='';
        
        $author=$html->find('section.contacts');
        if (count($author)>0)
        {
            $name=$author[0]->find('li.name');
            $NAM=$name[0]->plaintext;
            $phone=$author[0]->find('a.phone');
            foreach ($phone as $ph)
            {$TEL.=$ph->plaintext.';';}
        }
        
        $NAM=str_replace('vasitəçi (agent)',' vasitəçi (agent)',$NAM);
        //$author=$html->find('sections.contacts');
        //if (count($author)>0)
        //{
        //    $name=$author[0]->find('li.name');
        //    $NAM=$name[0]->plaintext;
        //    $phone=$author[0]->find('a.phone');
        //    foreach ($phone as $ph)
        //    {$TEL.=$ph->plaintext.';';}
        //}
                
        $sql="UPDATE `bina_info` SET `phone`='".$TEL."', `name`='".$NAM."', `status`='1' WHERE (`link`='".$row["link"]."')";
        echo $sql.'<br>';
        mysqli_query($link, $sql);
        Inlog ("bina.az","GetInfo",$row["link"],"");
        //exit;
    }
    
    
    
    
} 



?>
