<?php
require_once('../config.php');
header('Content-Type: application/json');

function get_balance() {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'http://api.reimaginebanking.com/accounts/' . CAPITAL_ONE_USER_ID . '?key=' . CAPITAL_ONE_API_KEY);
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

$bal = get_balance();

echo json_encode($bal->balance);