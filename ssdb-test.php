<?php

include(dirname(__FILE__) . '/SSDB.php');
$host = '127.0.0.1';
$port = 8888;

$log = __DIR__ . '/log.txt';

function ssdblog($data){
    global $log;
    file_put_contents($log, date('Y-m-d H:m:s').' '.$data."\n", FILE_APPEND);
}

try{
    $b=0;
    while($b<10){
        $i = 0;
        $ssdb = new SimpleSSDB($host, $port);
        while ($i < 10) {
            for($j=$i; $j<10;$j++){
                $key = "key:".$j;
                $ssdb->set($key, $j);
                ssdblog(vsprintf("write key: %s, value:%s", array($key, $j)));
                $v = $ssdb->get($key);
                ssdblog(vsprintf("get key: %s, value:%s", array($key, $v)));
                $dv = $ssdb->del($key);
                ssdblog(vsprintf("del key: %s, result:%s", array($key, $dv)));
            }

            $i++;
        }
        $ssdb->close();

        $ssdb = new SimpleSSDB($host, $port);
        $i=0;
        while ($i < 10) {
            for($j=$i; $j<10;$j++){
                $key = "hkey:".$j;
                $field = "hfield:".$j;
                $ssdb->hset($key, $field, $j);
                ssdblog(vsprintf("write key: %s, field: %s, value:%s", array($key, $field, $j)));
                $v = $ssdb->hget($key, $field);
                ssdblog(vsprintf("get key: %s, field: %s, value:%s", array($key, $field, $v)));
                $dv = $ssdb->hdel($key, $field);
                ssdblog(vsprintf("del key: %s, field: %s, result:%s", array($key, $field, $dv)));
            }

            $i++;
        }
        $ssdb->close();

        $b++;
        sleep(1);
    }

}catch(Exception $e){
    if($ssdb != null) $ssdb->close();
    ssdblog('Exception:'.$e->getMessage());
}


