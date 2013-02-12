<?php

$title = "- Főoldal";
 
$include_list[ 'head' ]		= array('pages/header.inc.php');
$include_list[ 'main' ]	    = array('pages/index.inc.php');
$include_list[ 'foot' ]	    = array('pages/footer.inc.php');

include("generate.php");
?>