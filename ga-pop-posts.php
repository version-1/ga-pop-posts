<?php
/*
Plugin Name: Google Analytics POP posts
Plugin URI: http://www.example.com/plugin
Description: get popular posts based on Google Analytics in your blog
Author: version1
Version: 0.1
Author URI: http://www.example.com/plugin
*/

$GA_POP_KEY_FILE_LOCATION = __DIR__ . '/uploads/files/service-account-credentials.json';
$GA_POP_CACHE_FILE_LOCATION = __DIR__.'/cache/posts.tmp';
$ENABLE_CACHE = true;
$GA_POP_DEFAULT_DISPLAY_COUNT = 5;
$GA_POP_DEFAULT_DATE_FROM_NUM = 90;

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/GA_POP_Cache.php';
require_once __DIR__ . '/classes/GA_POP_GAReport.php';
require_once __DIR__ . '/classes/GA_POP_Configuration.php';
require_once __DIR__ . '/classes/GA_POP_Widget.php';
require_once __DIR__ . '/classes/GA_POP_Renderer.php';
require_once __DIR__ . '/classes/GA_POP_TableRenderer.php';


function get_pop_posts(){
    global $GA_POP_KEY_FILE_LOCATION;
    global $GA_POP_DEFAULT_DISPLAY_COUNT;

    $cache = GA_POP_Cache::load_cache();
    if(!$ENABLE_CACHE || !$cache){
        $options = get_option( 'ga_pop_setting' );
        $view_id = $options['view_id'];
        $date_from = $options['date_from'].'daysAgo';
        $count = $options['show_list'];
        $exclude_urls = explode(',',str_replace(PHP_EOL,'',$options['exclude_url']));

        if(!file_exists($GA_POP_KEY_FILE_LOCATION)){
            GA_POP_Renderer::html_error_render('MISS_KEY_FILE');
            return false;
        }

        if(!$view_id){
            GA_POP_Renderer::html_error_render('UNSET_VIEW_ID');
            return false;
        }

        if ( ! isset($count) || $count < 1){
            $count = $GA_POP_DEFAULT_DISPLAY_COUNT;
        }

        get_rankings($count ,$GA_POP_KEY_FILE_LOCATION ,$view_id ,$date_from ,$exclude_urls);
    }else{
        echo $cache;
    }
}

function get_rankings($count = 10, $key_file , $view_id,$date_from,$exclude_urls ){
    $report = new GA_POP_GAReport($key_file ,$view_id , $date_from,$exclude_urls );
    $report->getReport();
    $posts = $report->fetchResults($count);
    $html = new GA_POP_TableRenderer($posts ,[ 'size' => [100,100],'attr' => [ 'class' => 'pop-posts-thumbnail']]);
    $html->render();
}
