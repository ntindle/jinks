<?php
header("Access-Control-Allow-Origin: *");

$post = file_get_contents('php://input');
$data = json_decode($post);

var_dump("Hi Ibrahaim", $data);

error_log(var_export($post, true));