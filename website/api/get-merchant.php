<?php
require_once('../config.php');
header('Content-Type: application/json');

function get_merchant($id) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'http://api.reimaginebanking.com/merchants/' . $id . '?key=' . CAPITAL_ONE_API_KEY);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


	$headers = array();
	$headers[] = 'Accept: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	curl_close ($ch);
	
	return $result;
}