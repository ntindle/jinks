<?php
header("Access-Control-Allow-Origin: *");

$post = file_get_contents('php://input');
$data = json_decode($post);

var_dump($data);

error_log(var_export($data, true));