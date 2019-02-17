<?php
header("Access-Control-Allow-Origin: *");

$post = file_get_contents('php://input');

var_dump("Hi Ibrahaim");

file_put_contents("a.3gp", base64_decode($post));

error_log("GOT MESSAGE: " . substr($post, 0, 25));