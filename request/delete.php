<?php

include '../fn.php';

$str = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$last = explode("/", $str, 4);
$last = $last[3];
$last = explode('?',$last,2);
$last = $last[0];


include 'requestFunctions.php';
header('Content-Type: application/json; charset=utf-8');
echo json_encode(call_user_func_array($last, array(&$_DELETE)));