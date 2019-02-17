<?php
require('../config.php');

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

var_dump(create_transaction('5c688e486759394351bec0e7', '110', 'Nick smells like...', '2019-02-16'));