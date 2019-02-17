<?php
require('../config.php');
header('Content-Type: application/json');

function get_all_merchants() {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'http://api.reimaginebanking.com/merchants?key=' . CAPITAL_ONE_API_KEY);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


	$headers = array();
	$headers[] = 'Accept: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	curl_close($ch);
	
	return $result;
}

$merchants = get_all_merchants();

$formatted_merchant_list = array();

foreach ($merchants as $merchant) {
	if (!empty($merchant->address->city) && !empty($merchant->address->state)) {
		$formatted_merchant_list[] = array($merchant->_id, $merchant->name . ', ' . $merchant->address->city . ', ' . $merchant->address->state);
	}
}

echo json_encode($formatted_merchant_list);