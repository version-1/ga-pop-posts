<?php

class GA_POP_StatusManager {
  private static $status = '';

  public static function getInstance(){
    static $instance = null;
    if (null === $instance) {
      $instance = new static();
    }
    return $instance;
  }

  public function is_normal(){
    return ! $this->status;
  }

  public function set($status){
    $this->status = $status;
  }

  public function get(){
    return $this->status;
  }
}
