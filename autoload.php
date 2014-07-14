<?php
function __autoload($className) {
	if (file_exists(__DIR__.'/classes/'.$className . '.php')) { 
          require_once __DIR__.'/classes/'.$className . '.php'; 
          return true; 
      } 
      return false; 
}


?>