<?php

class GA_POP_HTMLGenerator {
  protected $posts;
  protected $options;
  protected $generate;
  private $html;

  function __construct($posts,$options){
    $this->posts = $posts;
    $this->options = $options;
    $this->generate($options);
  }

  function generate(){
    foreach($this->posts as $post){
      $body = $body."<li></span><a href='".$post->get_path_name()."'>".$post->get_title()."</a></li>\n";
    }
    $html = "<ul style='list-style:none;padding-left:0px'>".$body."</ul>";
    $this->html = $html;
  }

  function get(){
    return $this->html;
  }

}
