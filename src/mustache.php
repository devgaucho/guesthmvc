<?php
namespace gaucho;
use Simplon\Mustache\Mustache as Simplon;
class mustache{
	function renderFromString($template,$data=[]){
		return Simplon::render($template,$data);
	}
	function renderFromFile($filename,$data=[]){
		if(file_exists($filename)){
			$template=file_get_contents($filename);
			return $this->renderFromString($template,$data);
		}else{
			die(htmlentities($filename).' not found');
		}
	}
}