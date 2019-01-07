<?php

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

//echo "$myData";
$data = str_getcsv($myData);
$data = array_chunk($data, 4);
$fp = fopen('router.db', 'a');
foreach ($data as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);


