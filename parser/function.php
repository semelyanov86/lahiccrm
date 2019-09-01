<?php

function InLog($site,$action,$value,$res){
    $log_file_name = "parser.log";
    $now = date("Y-m-d H:i:s");
    file_put_contents($log_file_name, $now." ".$site." ".$action." ".$value." ".$res."\r\n", FILE_APPEND);
}

?>