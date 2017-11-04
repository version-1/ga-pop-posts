<?php

class GA_POP_Cache {

    public static  $tmp_file;

    function __construct() {
        global $GA_POP_CACHE_FILE_LOCATION;
        $this->tmp_file = $GA_POP_CACHE_FILE_LOCATION;
    }

    public static function load_cache(){
        global $GA_POP_CACHE_FILE_LOCATION;
        $filename = $GA_POP_CACHE_FILE_LOCATION;

        $filetime = filemtime($filename);
        $expire_time = date('Y-m-d H:i:s', strtotime('-1 day', time()));
        $ftime = date('Y-m-d H:i:s',$filetime);

        if(!$filetime || strtotime($expire_time) > strtotime($ftime)){
            return false;
        }
        return file_get_contents($filename);
    }

    public static function clear_cache(){
        global $GA_POP_CACHE_FILE_LOCATION;
        if (file_exists($GA_POP_CACHE_FILE_LOCATION)){
            return unlink($GA_POP_CACHE_FILE_LOCATION) ; 
        }
    }
}
