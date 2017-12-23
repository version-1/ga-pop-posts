<?php

class GA_POP_Renderer {
  public function render($status, $html){
    if($status){
      var_dump($status);
      $this->error_render();
    }else{
      echo $html;
    }
  }

  private function error_render(){
    $message = '';
    switch ($this->$status) {
      case 'MISS_KEY_FILE':
      $message =  "<p>Failed to get articles.Private Key File is Not Found.Please confirm your Google Analytics POP Posts settings</p>";
      break;
      case "UNSET_VIEW_ID":
      $message = "<p>Failed to get articles.View ID is not set. Please confirm your configuration</p>";
      break;
      default:
      $message = "<p>Failed to get articles.Please confirm your Google Analytics POP Posts settings</p>";
      break;
    }

    if(WP_DEBUG){
      echo $message;
    }
  }

}
