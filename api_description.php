<?php

$title = "Worklog API";
 
$include_list[ 'head' ]		= array('pages/header.inc.php');
$include_list[ 'main' ]	    = array('pages/api_description.inc.php');
$include_list[ 'foot' ]	    = array('pages/footer.inc.php');

include("generate.php");
?>