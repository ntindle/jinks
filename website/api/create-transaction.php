<?php
require_once('../config.php');
require('send-push-notification-android.php');
require('get-merchant.php');

header('Content-Type: application/json');

function get_words($n) {
	$dictionary = file_get_contents('eff_large_wordlist.txt');
	$words = "";
	for ($i = 0; $i < $n; $i++) {
		$random = rand(1,6) . rand(1,6) . rand(1,6) . rand(1,6) . rand(1,6);
		preg_match("@^$random\t(.+)@m", $dictionary, $m);
		$words .= $m[1] . ' ';
	}
	return trim($words);
}

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

$transaction = create_transaction($merchant, $amount, $description, $date);
$transaction_id = $transaction->objectCreated->_id;

$words = "";

if (floatval($amount) > 100) {
	$transaction->status = 0;

	$merchant = get_merchant($merchant);

	$push = send_push_notification_android(
		ANDROID_DEVICE_ID,
		'Check transaction: $' . $amount . ' @ ' . $merchant->name,
		array('id' => $transaction_id)
	);
	$words = get_words($dictionary, 5);
} else {
	$transaction->status = 3;
}
$q = $db->query('INSERT INTO transaction_log (transaction_id, status, word_challenge)
	VALUES (
		"' . $db->real_escape_string($transaction_id) . '",
		"' . $db->real_escape_string($transaction->status) . '",
		"' . $words . '"
	)') or die($db->error);

$transaction->words = $words;

error_log(var_export($push, true));

echo json_encode($transaction);