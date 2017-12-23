<?php

class GA_POP_HTMLTableGenerator extends GA_POP_HTMLGenerator {

	function generate(){
		foreach($this->posts as $post){
			$body = $body."<tr><td><a href='".$post->get_path_name()."'>{image}</a></td><td>{title}</td></tr>";
			$size = $this->options['size'] ? $this->options['size'] : [100,100];
			$attr = $this->options['attr'] ? $this->options['attr'] : [];

			$thumbnail = get_the_post_thumbnail($post->get_post_id() , $size , $attr);
			$title_link = "<a href='".$post->get_path_name()."'>".$post->get_title()."</a></li>\n";

			$body = str_replace('{image}',$thumbnail,$body);
			$body = str_replace('{title}',$title_link,$body);
		}

		$this->html = "<table>".$body."</table>";
	}

	function get(){
		return $this->html;
	}
}
