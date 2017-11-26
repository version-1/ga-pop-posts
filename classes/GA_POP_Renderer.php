<?php

class GA_POP_Renderer {
    protected $posts;
	protected $options;
	protected $generate;

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

	function render(){
	    echo $this->html;
	    global $GA_POP_CACHE_FILE_LOCATION;
	    file_put_contents($GA_POP_CACHE_FILE_LOCATION,$this->html);
	}

    public static function html_error_render($type){
		$message = '';
		switch (type) {
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
