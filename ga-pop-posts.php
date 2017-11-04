<?php
/*
Plugin Name: Google Analytics POP posts
Plugin URI: http://www.example.com/plugin
Description: get popular posts in your blog
Author: version1
Version: 0.1
Author URI: http://ver-1-0.net
*/

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/Cache.php';
require_once __DIR__ . '/classes/Post.php';
require_once __DIR__ . '/classes/GAReport.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/widget.php';

function get_pop_posts($attr){
    $cache = Cache::load_cache();
    if(!$cache){
        $KEY_FILE_LOCATION = __DIR__ . '/uploads/files/service-account-credentials.json';
        $DEFAULT_DISPLAY_COUNT = 10;
        $VIEW_ID = '';
        $DATE_FROM = '90daysAgo';
        if ( ! isset($attr[0]) || $attr[0] < 1){
            $count = $DEFAULT_DISPLAY_COUNT;
        }else{
            $count = $attr[0];
        }
        get_rankings($count,$KEY_FILE_LOCATION, $VIEW_ID,$DATE_FROM);
    }else{
        echo $cache;
    }
}

function get_rankings($count = 10, $key_file , $view_id,$date_from){
    $report = new GAReport($key_file ,$view_id , $date_from);
    $report->getReport();
    $posts = $report->fetchResults($count);
    html_render($posts);
}

function html_render($posts){
    $html = "<ul style='list-style:none;padding-left:0px'>";
    foreach($posts as $post){
       $html = $html."<li></span><a href='".$post->get_path_name()."'>".$post->get_title()."</a></li>\n";
    }
    $html = $html."</ul>";
    echo $html;
    file_put_contents(__DIR__.'/cache/posts.tmp',$html);
}
