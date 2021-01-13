<?php
$fp = fopen("C:/Apache24/htdocs/004/logs/app-tt.log", 'a+');
fwrite($fp, date('Y-m-d G:i:s').'  '.'127.0.0.1'.' '.'sdcdscdscdsc'."\n");
fclose($fp);