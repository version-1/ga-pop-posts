<?php

Class GA_POP_Database{
  public static function get_tilte_by_path_name($post_name){
    global $wpdb;
    $query = $wpdb->prepare('select post_title from wp_posts where post_name = %s',$post_name);
    return $wpdb->get_var($query,0,0);
  }
  public static function get_post_by_path_name($post_name){
    global $wpdb;
    $query = $wpdb->prepare('select id, post_title from wp_posts where post_name = %s',$post_name);
    return $wpdb->get_results($query, ARRAY_A)[0];
  }
}
