<?php

function recursive_change_key($arr, $set) {
        if (is_array($arr) && is_array($set)) {
     $newArr = array();
     foreach ($arr as $k => $v) {
    		    $key = array_key_exists( $k, $set) ? $set[$k] : $k;
    		    $newArr[$key] = is_array($v) ? recursive_change_key($v, $set) : $v;
     }
     return $newArr;
     }
     return $arr;    
    }


$data = file_get_contents("php://input");
parse_str($data, $data);


$yaml_array = yaml_parse_file('config');
$keys = array_keys($yaml_array['groups']);

if ($data['action'] == "edit" && !empty($data['location'])) {
unset($data['action']);
$yaml_array['groups'] = recursive_change_key($yaml_array['groups'], array($keys[$data['id']] => $data['location']));

$yaml_array['groups'][$data['location']]['username'] = $data['username'];
$yaml_array['groups'][$data['location']]['password'] = $data['password'];
if (!$data['enable'] == null) {
$yaml_array['groups'][$data['location']]['vars']['enable'] = $data['enable'];
} else {
unset($yaml_array['groups'][$data['location']]['vars']);
}

file_put_contents("config",yaml_emit($yaml_array));

} elseif ($data['action'] == "delete") {

unset($yaml_array['groups'][$keys[$data['id']]]);
file_put_contents("config",yaml_emit($yaml_array));
}


?>
