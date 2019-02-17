<?php
require_once('../config.php');
require('get-merchant.php');
header('Content-Type: application/json');

function get_all_purchases() {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'http://api.reimaginebanking.com/accounts/' . CAPITAL_ONE_USER_ID . '/purchases?key=' . CAPITAL_ONE_API_KEY);
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

$purchases_list = array();

$purchases = get_all_purchases();
foreach ($purchases as $purchase) {
	$q = $db->query('SELECT status FROM transaction_log WHERE transaction_id = "' . $db->real_escape_string($purchase->_id) . '"');
	$r = $q->fetch_array(MYSQLI_NUM);
	$status = $r[0];
	$purchases_list[] = array(
		'id' => $purchase->_id,
		'amt' => $purchase->amount,
		'date' => $purchase->purchase_date,
		'desc' => $purchase->description,
		'status' => $status,
		'merchant' => get_merchant($purchase->merchant_id)
	);
}

echo json_encode($purchases_list);