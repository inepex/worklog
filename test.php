<?php
/*
##########################################################################
#                   Free PHP Benchmark Performance Script                #
#                             Version 2.0                                #
#                        © 2006 Free-Webhosts.com                        #
#                        © 2006 Nick Barrett	                         #
#  License     :  FREE (GPL)                                             #
#                                                                        #
##########################################################################
#
# It is recommended to host this script in a password-protected folder if possible; or otherwise to exclude it in robots.txt and rename it something more obscure.
# The script is used to compare the web server speeds of PHP web hosting servers.
#
*/


//Handle session data
if(isset($_GET['test'])) {
	$test = $_GET['test'];
	session_start();
	$stored_results = $_SESSION['results'];
}
else {
	$test = 0;
	if(isset($_SESSION['results'])) {
		session_destroy();
	}
	session_start();
	$_SESSION['results'] = array(); 
}



function stopWatch ($start, $end) {
	$starttime = explode(' ', $start);
	$endtime = explode(' ', $end);
	$total_time = $endtime[0] + $endtime[1] - ($starttime[1] + $starttime[0]);
	$total_time = $total_time * 1000;
	return $total_time;
}


function getCpuInfo() {
	$cpu = "unknown";
	$mhz = 0;
	$num_cpus = 1;
	
	if(strpos(php_uname(), "Windows") === false) {
		$result = array();
		$output = @exec("cat /proc/cpuinfo | grep \"model name\\|processor\"", $result);
		$cpuinfo = $result[1];
		
		$num_cpus = count($result) / 2;
		$model = strrpos($cpuinfo, ":") + 1;
		$cpu = trim(substr($cpuinfo, $model));	
		$mhz = intval($cpu);
		
	}
	else {
		$result = array();
		$output = @exec("cpuinfo.exe", $result);
		$cpu = trim(substr($result[7],22));
		$mhz = trim(substr($result[4],22,-41));
		$num_cpus = getenv("NUMBER_OF_PROCESSORS");
		
	}
	return array($cpu, $mhz, $num_cpus);
}


//FILE-WIDE STATIC VARIABLES
$test_ver = "1.1";
$refresh = 5; //The number of seconds between iterations
$iterations = 10; //The number of iterations of the test (between 10-12 recommended)
$title = "PHP Benchmark Performance Script";
$logo = "http://static.php.net/www.php.net/images/logos/php-med-trans-light.gif";



//Set page to reload to itself
if(isset($_SERVER['PHP_SELF'])) {
	$filename = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/") + 1);
	$PHP_SELF = $_SERVER['PHP_SELF'];
}

$starttime = microtime(); //Start the clock!



//START OF TESTS

//Test 1 - String and integer manipulation
$s1 = "Strings";
$t1s = microtime();
$string1 = 'abcdefghij';
for($i = 1; $i <= 30000; $i++) {
	$x=$i * 5;
	$x=$x + $x;
	$x=$x/10;
	$string3 = $string1 . strrev($string1);
	$string2 = substr($string1, 9, 1) . substr($string1, 0, 9);
	$string1 = strtoupper($string2);
}
$t1 = stopWatch($t1s, microtime());


//Test 2 - Encryption and hashing
$s2 = "Encryption";
$t2s = microtime();
$string2 = "This is a test string to see how fast your web server can process PHP functions";
for($i=0; $i < 150; $i++) {
	$md5 = md5($string2);
	$sha1 = sha1($md5);
	$crc32 = crc32($sha1);
	$cryptDate = crypt($crc32);
}
$t2 = stopWatch($t2s, microtime());


//Test 3 - Date functions
$s3 = "Dates";
$t3s = microtime();
for($i=0; $i < 300; $i++) {
	$string3 = date("D M d Y"). ', News Years Day : ' .date("M-d-Y", mktime(0, 0, 0, 1, 1, 1998));
	$secNextWeek = time() + (7 * 24 * 60 * 60);
	date('Y-m-d', $secNextWeek);
}
$t3 = stopWatch($t3s, microtime());


//Test 4 - Image manipulation
$s4 = "Images";
$t4s = microtime();
for($i=0; $i < 1000; $i++) {
	$im = @imagecreatetruecolor(200, 200)
		 or die("Cannot Initialize new GD image stream");
	$text_color = imagecolorallocate($im, 233, 14, 91);
	imagestring($im, 1, 5, 5,  "A Simple Text String", $text_color);
	imagedestroy($im);
	unset($im);
}
$t4 = stopWatch($t4s, microtime());


//Test 5 - Getting system variables and array management
$s5 = "Arrays";
$t5s = microtime();
for($i=0; $i < 1000; $i++) {
	$array = $_SERVER;
	shuffle($array);
	foreach ($array as $value) {
		ucfirst($value);
	}
	arsort($array);
}
$t5 = stopWatch($t5s, microtime());



//Test 6 - File system functions
$s6 = "Filesystem";
$t6s = microtime();
for($i=0; $i < 100; $i++) {
	//Subtest 6.1 - reading files
	$dataFile = fopen( $filename, "r" ) ;
	if($dataFile) {
		while (!feof($dataFile)) {
			$buffer = fgets($dataFile, 4096);
	   }
	   fclose($dataFile);
	}
	else {
	   die( "fopen failed for $filename" ) ;
	}
	
	//Subtest 6.2 - read file metadata
	$filesize = @filesize($filename);
	$isreadable = @is_readable($filename);
	$iswritable = @is_writable($filename);
	$df = @disk_free_space("/");	
	
	//Subtest 6.3 - copy and deleting files
	if($i < 7) {
		$file2 = "benchmark2.php";
		$copy = @copy($filename, $file2);
		if(!$copy) {
			//echo "failed to copy $filename...\n";
		} else {
			unlink($file2);
		}
	}
}
$t6 = stopWatch($t6s, microtime());


//Test 7 - Objects
$s7 = "Objects";
$t7s = microtime();

class Foo {
	var $z;
	function Foo($var) {
		$this->z = $var;
	}
	function do_foo() {
       return $this->z;
    }
	function multiply($var1, $var2) {
		return ($var1 * $var2);
	}
}

for($i=0; $i < 10000; $i++) {
	$bar = new Foo("Hello world!");
	$_1 = $bar->do_foo();
	$_2 = $bar->multiply(72, 12);
}
$t7 = stopWatch($t7s, microtime());




//END OF TEST
$endtime = microtime(); //Stop the clock!

//create the results array to put in the session array
$results = array($t1, $t2, $t3, $t4, $t5, $t6, $t7);
$subtests = array($s1, $s2, $s3, $s4, $s5, $s6, $s7);


###################################################


$tquery = 'test=' . ($test + 1);
$tquery2 = $tquery . '&stop=1';

//put the scores in the appropriate index
$stored_results[$test] = $results;
$_SESSION['results'] = $stored_results;


//If we're done, stop!
if(isset($_GET['stop'])) {
	$stop = $_GET['stop'];
}
if(isset($stop)) {
	$test = $iterations;
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php
if(!isset($_GET['stop'])) {
	if($test + 1 < $iterations) {
		echo '<meta http-equiv="REFRESH" content="'.$refresh.'; url=' . $PHP_SELF . '?' . $tquery . '" />';
	}
}
?>
<title><?php echo $title; ?></title>
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
<meta content="text/html; charset=utf-8" http-equiv="content-type" />

<style type="text/css">
	body {
		font-family: Tahoma, Verdana, Arial, sans-serif;
		font-size: 13px;
	}
	h1 {
		font-size: 28px;
		color: navy;
	}
	tr {
		font-size: 13px;
	}
	.header {
		font-weight: bold;
		background-color: navy;
		color: #eee;
	}
	.subtests {
		background-color: #888;
		color: #fff;
	}
	.results {
		font-weight: bold;
		background-color: #ccc;
	}
	#iterations {
		width: 620px;
		position:absolute;
		left:10px;
		top:135px;
	}
	#showresults {
		position:absolute;
		left:640px;
		top:135px;
	}

</style>
</head>

<body>
<h2><?php echo $title; ?></h2>
<?php

//Display info from server that the test is running from:
echo '<a href="http://www.php.net" target="_blank"><img src="'.$logo.'" border="0" align="left" alt="PHP" title="PHP" style="padding-right: 10px;" /></a>';
echo '<p>Server: '.$_SERVER["HTTP_HOST"].'<br />';
echo $_SERVER['SERVER_SOFTWARE'].'<br />';
echo date("F j, Y, g:i a").'<br />';
echo 'More info: <a href="http://benchmarks.nickbarrett.org">http://benchmarks.nickbarrett.org</a>';
echo '</p>';
echo '<div id="iterations">';

//Calculate the results
$y = count($stored_results);
$x = count($stored_results[0]);

if($y > 0 ) {

	$overall = range(0, $y-1);
	$overall_time = 0;
	$subscores_time = array_fill(0, $x, 0);

	
	echo '<table border="0" width="600" cellpadding="5" cellspacing="0" style="border: solid 1px black;">'."\n";
	echo '<tr class="header"><td rowspan="2">Test</td><td colspan="'.count($subtests).'" align="center">Subscores</td><td rowspan="2">Overall</td></tr>'."\n";
	echo '<tr class="subtests" align="center">';
	for($s=0; $s < count($subtests); $s++) {
		echo '<td>'.$subtests[$s].'</td>'."\n";
	}
	echo '</tr>';	
	
	//Show the iteration results
	$shaded = false;
	for($i=0; $i < $y; $i++) {
		$i_rs = $stored_results[$i];
		
		if(!$shaded) {
			echo '<tr>';
			$shaded = true;
		} else {
			echo '<tr bgcolor="#eeeeee">';
			$shaded = false;
		}
		
		echo '<td>'.($i+1).'</td>'."\n";
		$iter_time = 0;
		
		for($b=0; $b < count($i_rs); $b++) {
			$t = $i_rs[$b];
			echo '<td>'.round($t,0).'</td>'."\n";
			$iter_time += $t;
			$subscores_time[$b] += $t;
		}
		$iter_time = round($iter_time,0);
		echo '<td>'.$iter_time.'</td></tr>'."\n";
		$overall[$i] = $iter_time;
		$overall_time += $iter_time;
	}

	//Calculate statistics on the results
	sort($overall);
	$lowest = $overall[0];
	$highest = $overall[count($overall)-1];
	$average = round(($overall_time / $y), 0);

	echo '<tr class="results"><td>Avg</td>';
	for($s=0; $s < count($subscores_time); $s++) {
		echo '<td>'.round($subscores_time[$s] / $y).' ms</td>';
	}
	echo '<td>'.$average.' ms</td></tr>';
	echo '</table>';
	
	
	echo '<p>';
	echo "Lowest time: $lowest ms, Highest time : $highest ms<br />\n";
	if($test > 2) 
	{
		$a = $y - 2;
		$AverageMid = round(($overall_time-$lowest-$highest) / $a);
		echo "Average of middle $a times: $AverageMid ms<br />\n";
	}
	echo '</p>';
	
	
}

echo '<p><a href="' . $PHP_SELF . '"><b>Begin again</b></a>';
if($test + 1 < $iterations) {
	echo ' | <a href="' . $PHP_SELF . '?' . $tquery2 . '"><b>Stop</b></a> | <font color=red>Doing ' . $iterations . ' iterations, '.($iterations - $test - 1).' remaining. Refreshing in '.$refresh.' seconds...';
}
echo '</p>';
?>

  <p>
	<a href="http://validator.w3.org/check?uri=referer">
		<img src="http://www.w3.org/Icons/valid-xhtml10" border="0" alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
	<a href="http://jigsaw.w3.org/css-validator/">
		<img style="border:0;width:88px;height:31px" border="0" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" /></a>
  </p>
</div>
<!-- end of iterations div -->


<?php
if(!($test + 1 < $iterations)) {
	echo '<div id="showresults">';
	echo '<form name="submit_scores" action="http://benchmarks.nickbarrett.org/submit.php" method="post">';

	echo '<table cellpadding="3" cellspacing="0" bgcolor="#f0f0e2" style="border: solid 1px black;">';
	echo '<tr class="subtests"><td colspan="2"><b>Your System Details</b></td></tr>';
	
	$cpuinfo = getCpuInfo();
	$cpu = $cpuinfo[0];
	$mhz = $cpuinfo[1];
	$num_cpus = $cpuinfo[2];
	
	echo '<tr><td>CPU</td><td>'.$cpu.'<input type="hidden" name="CPU" value="'.$cpu.'" /></td></tr>';
	echo '<tr><td>Speed</td><td><span id="mhz">'.$mhz.'</span> MHz<input type="hidden" name="MHz" value="'.$mhz.'" /></td></tr>';
	echo '<tr><td>OS</td><td>'.php_uname().'</td><input type="hidden" name="OS" value="'.php_uname().'" /></tr>';
	echo '<tr><td>PHP</td><td>'.phpversion().'<input type="hidden" name="PHP" value="'.phpversion().'" /></td></tr>';
	echo '<tr><td>API</td><td>'.php_sapi_name().'<input type="hidden" name="API" value="'.php_sapi_name().'" /></td></tr>';
	echo '<tr><td>Zend</td><td>'.zend_version().'<input type="hidden" name="Zend" value="'.zend_version().'" /></td></tr>';
	echo '<tr><td>Avg. Time</td><td>'.$average.' ms<input type="hidden" name="Overall" value="'.$average.'" /></td></tr>';
	echo '</table>';

	echo '<br />';
	for($s=0; $s < count($subscores_time); $s++) {
		echo '<input type="hidden" name="'.$subtests[$s].'" value="'.round($subscores_time[$s] / $y).'" />'."\n";
	}
	echo '<input type="hidden" name="host" value="'.$_SERVER["HTTP_HOST"].'" />'."\n";
	echo '<input type="hidden" name="datetime" value="'.date('Y-m-d h:i:s').'" />'."\n";
	echo '<input type="hidden" name="num_cpus" value="'.$num_cpus.'" />'."\n";
	echo '<input type="hidden" name="sha1" value="'.sha1_file($filename).'" />'."\n";
	echo '<input type="hidden" name="test_ver" value="'.$test_ver.'" />'."\n";
	echo '<input type="submit" value="Submit Your Scores" />';
	echo '</form>';
	echo '</div>';
}

?>

</body></html>
