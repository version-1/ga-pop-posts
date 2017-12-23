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
$GA_POP_CACHE_FILE_LOCATION = __DIR__ . '/cache/posts.tmp';
$GA_POP_ENABLE_CACHE = true;
$GA_POP_DEFAULT_CACHE_EXPIRED_IVAL = 24;
$GA_POP_DEFAULT_DISPLAY_COUNT = 5;
$GA_POP_DEFAULT_DATE_FROM_NUM = 90;
$GA_POP_CACHE_KEY = 'ga_pop_posts';

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/autoload.php';

function get_pop_posts(){
  global $GA_POP_KEY_FILE_LOCATION;
  global $GA_POP_DEFAULT_DISPLAY_COUNT;
  global $GA_POP_DEFAULT_CACHE_EXPIRED_IVAL;
  global $GA_POP_CACHE_FILE_LOCATION;
  global $GA_POP_ENABLE_CACHE;

  $options = get_option( 'ga_pop_setting' );
  $expired_ival = $options['expired_ival'] ?: $GA_POP_DEFAULT_CACHE_EXPIRED_IVAL;

  $status = GA_POP_StatusManager::getInstance();
  $cache = GA_POP_CacheManager::getInstance($GA_POP_CACHE_FILE_LOCATION,$expired_ival);
  $ele = $cache->get();
  if(!$GA_POP_ENABLE_CACHE || !$ele){

    $view_id = $options['view_id'];
    $date_from = $options['date_from'].'daysAgo';
    $count = $options['show_list'];
    $exclude_urls = explode(',',str_replace(PHP_EOL,'',$options['exclude_url']));

    if(!file_exists($GA_POP_KEY_FILE_LOCATION)){
      $status->set('MISS_KEY_FILE');
    }

    if(!$view_id){
      $status->set('UNSET_VIEW_ID');
    }

    if ( ! isset($count) || $count < 1){
      $count = $GA_POP_DEFAULT_DISPLAY_COUNT;
    }
    if ($status->is_normal()){
      $html = get_rankings($count ,$GA_POP_KEY_FILE_LOCATION ,$view_id ,$date_from ,$exclude_urls);
      $ele = $html->get();
      $cache->set($ele);
    }
  }
  $renderer = new GA_POP_Renderer();
  $renderer->render($status->get(), $ele);
}

function get_rankings($count = 10, $key_file , $view_id,$date_from,$exclude_urls ){
  $report = new GA_POP_GAReport($key_file ,$view_id , $date_from,$exclude_urls );
  $report->getReport();
  $posts = $report->fetchResults($count);
  $html = new GA_POP_HTMLTableGenerator($posts ,[ 'size' => [100,100],'attr' => [ 'class' => 'pop-posts-thumbnail']]);
  return $html;
}
