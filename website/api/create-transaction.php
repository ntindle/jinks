<?php
require('../config.php');
header('Content-Type: application/json');

function create_transaction($merchant_id, $amount, $description, $date) {
	$ch = curl_init();

	$post = array('merchant_id' => $merchant_id,
				  'medium' => 'balance',  
				  'purchase_date' => $date,//'2019-02-17',
				  'amount' => floatval($amount),
				  'status' => 'pending',
				  'description' => $description
				 );
	
	curl_setopt($ch, CURLOPT_URL, 'http://api.reimaginebanking.com/accounts/' . CAPITAL_ONE_USER_ID . '/purchases?key=' . CAPITAL_ONE_API_KEY);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
	curl_setopt($ch, CURLOPT_POST, 1);

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Accept: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	
	curl_close($ch);
	
	return $result;
}

$amount = $_POST['amount'];
$merchant = $_POST['merchant'];
$date = $_POST['date'];
$description = $_POST['description'];

echo json_encode(create_transaction($merchant, $amount, $description, $date));