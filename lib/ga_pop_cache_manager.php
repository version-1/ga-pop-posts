<?php

class GA_POP_CacheManager {
  private $cache_file;
  private $expired_ival;

  public static function getInstance($file,$expired_ival = 24){
    static $instance = null;
    if (null === $instance) {
      $instance = new static();
      $instance->cache_file = $file;
      $instance->expired_ival = $expired_ival;
    }
    return $instance;
  }
  public function get(){
    if (!$this->is_expired($this->cache_file)){
      return file_get_contents($this->cache_file);
    }
    return false;
  }

  public function set($data){
    return file_put_contents($this->cache_file,$data);
  }

  public function delete(){
    if (file_exists($this->cache_file)){
      return unlink($this->cache_file);
    }
  }

  private function is_expired($filename){
    $filetime = file_exists($filename) ? filemtime($filename) : false;
    $expire_time = date('Y-m-d H:i:s', strtotime("-$this->expired_ival hour", time()));
    $ftime = date('Y-m-d H:i:s',$filetime);

    return !$filetime || strtotime($expire_time) > strtotime($ftime);
  }
}
