<?php

Class GA_POP_Database{
    public static function get_tilte_by_path_name($post_name){
        global $wpdb;
        $query = $wpdb->prepare('select post_title from wp_posts where post_name = %s',$post_name);
        return $wpdb->get_var($query,0,0);
    }
}
