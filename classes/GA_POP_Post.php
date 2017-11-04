<?php

class GA_POP_Post {

    private $path_name = '';
    private $post_name = '';
    private $title = '';
    private $page_view = 0;

    function __construct( $path_name,$page_view) {
         $this->path_name = $path_name;
         $this->post_name = urlencode(mb_convert_encoding(str_replace('/','',$path_name), 'UTF-8', 'auto'));
         $this->page_view = $page_view;
    }

    function get_path_name(){
        return $this->path_name;
    }

    function get_post_name(){
        return $this->post_name;
    }

    function set_title($title){
        $this->title = $title;
    }

    function get_title(){
        return $this->title;
    }
}
