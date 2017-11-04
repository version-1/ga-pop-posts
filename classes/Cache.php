<?php

class Cache {

    public static  $tmp_file;

    function __construct() {
        $this->tmp_file =__DIR__.'/../cache/posts.tmp';
    }

    public static function load_cache(){
        $filename = __DIR__.'/../cache/posts.tmp';
        $filetime = filemtime($filename);
        $expire_time = date('Y-m-d H:i:s', strtotime('-1 day', time()));
        $ftime = date('Y-m-d H:i:s',$filetime);

        if(!$filetime || strtotime($expire_time) > strtotime($ftime)){
            return false;
        }
        return file_get_contents($filename);
    }
}
