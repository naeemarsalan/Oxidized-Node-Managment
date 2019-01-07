<?php
require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    function strip_slashes($input) {
        if (!is_array($input)) {
            return stripslashes($input);
        }
        else {
            return array_map('strip_slashes', $input);
        }
    }
    $_GET = strip_slashes($_GET);
    $_POST = strip_slashes($_POST);
    $_COOKIE = strip_slashes($_COOKIE);
    $_REQUEST = strip_slashes($_REQUEST);
}

function customError($errno, $errstr) {
    echo "<b>Error:</b> [$errno] $errstr<br>";
    echo "Ending Script";
    die("Ending Script");
}
set_error_handler("customError");

$myData = $_GET["data"];
$myData = str_getcsv($myData);
$myData = array_chunk($myData, 4);

$yaml_array = yaml_parse_file('config');
$keys = array_keys($yaml_array['groups']);


//print_r($myData);
$array1 = [] ;
foreach ($myData as $i=>$rows) {
$array = array( $myData[$i][0] => array(
				     "username" => $myData[$i][1], "password" =>  $myData[$i][2], "vars" => array("enable" => $myData[$i][3])
				   )
	      );
array_push($array1,$array);
}
$newArr = array();
foreach ($array1 as $subarray)
    $newArr += $subarray;
//$dil = 'ruby/regexp /,/';
$dil = '/!ruby/regexp /,/';
$yaml_array['source']['csv']['delimiter'] = $dil;

$yaml_array['groups'] = array_merge ($yaml_array['groups'],$newArr);
file_put_contents("config",yaml_emit($yaml_array));
yaml_emit_file("testconfig", $yaml_array);
