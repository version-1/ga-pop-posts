<?php

$files = scandir(__DIR__);

foreach($files as $f){
	if(!is_dir($f) && $f != 'autload.php'){
		require_once __DIR__ .'/'. $f;
	}
}
