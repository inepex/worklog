<?php 
class Tools{
	public static function identify_link($text){
		$text = ' ' . $text;
		$text = preg_replace('`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i','$1<a href="$2">$2</a>',$text);
		$text = substr($text, 1);
		return $text;
		;
	}
}
?>