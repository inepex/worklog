<?php

$title = "Home";

$include_list[ 'head' ]		= array('pages/header.inc.php');
$include_list[ 'main' ]	    = array('pages/mobile.inc.php');
$include_list[ 'foot' ]	    = array('pages/footer.inc.php');

include("generate.php");
?>