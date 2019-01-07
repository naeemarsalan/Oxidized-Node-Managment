<?php

function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}



$data = file_get_contents("php://input");


parse_str($data, $data);

//print_r($data);

$csvFile = 'router.db';
$csv = readCSV($csvFile);
unset($csv[count($csv)-1]);
$csv = array_values($csv);


//print($data['action']);

if ($data['action'] == "edit" && !empty($data['Name'])) {

unset($data['action']);


$csv[$data['id']][0] = trim($data['Name']);
$csv[$data['id']][0] = str_replace(' ', '-', $csv[$data['id']][0]);
$csv[$data['id']][1] = trim($data['IP']);
$csv[$data['id']][2] = trim($data['Model']);
$csv[$data['id']][3] = trim($data['Location']);

//print_r($csv);
$rdb = fopen('router.db', 'w');

foreach ($csv as $fields) {
fputcsv($rdb,$fields);
}
fclose($fp);
} elseif ($data['action'] == "delete") {

unset($data['action']);
unset($csv[$data["id"]]);
$rdb = fopen('router.db', 'w');
foreach ($csv as $fields) {
fputcsv($rdb,$fields);
}
fclose($fp);
}

