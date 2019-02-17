<?php
require_once('../website/config.php');
header("Access-Control-Allow-Origin: *");

function update_status($status, $transaction_id, $emotions) {
	global $db;
	$q = $db->query('UPDATE transaction_log SET status = "' . $db->real_escape_string($status) . '" WHERE transaction_id = "' . $db->real_escape_string($transaction_id) . '"');
	
	exec(API_BASE . '/send.py ' . escapeshellarg($status) . ' ' . escapeshellarg($transaction_id) . ' ' . escapeshellarg($emotions));
}

function convert_to_wav($recording, $transaction_id) {
	$tmpfilein = tempnam(sys_get_temp_dir(), $transaction_id);
	$tmpfileout = tempnam(sys_get_temp_dir(), $transaction_id) . '.wav';
	file_put_contents($tmpfilein, $recording);
	exec('ffmpeg -i ' . escapeshellarg($tmpfilein) . ' ' . escapeshellarg($tmpfileout));
	$recording = file_get_contents($tmpfileout);
	unlink($tmpfilein);
	unlink($tmpfileout);
	return $recording;
}

function analyse_voice($recording) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://proxy.api.deepaffects.com/audio/generic/api/v2/sync/recognise_emotion?apikey=' . DEEP_AFFECTS_API_KEY);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$post = array(
		"content" => base64_encode($recording),
		"sampleRate" => 8000,
		"encoding" => "WAV",
		"languageCode" => "en-US"
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
	curl_setopt($ch, CURLOPT_POST, 1);

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	$result = json_decode($result);
	curl_close ($ch);
	
	return $result;
}

$post = file_get_contents('php://input');

$data = explode(' ', $post); // base64 id (base64 has no spaces)
$recording = base64_decode($data[0]);
$transaction_id = $data[1];

$recording = convert_to_wav($recording, $transaction_id);
$analysed_recording = analyse_voice($recording);

error_log(var_export($analysed_recording, true));

$emotions = array();
foreach ($analysed_recording as $emotion) {
	$emotions[] = $emotion->emotion;
}

$freq_emotions = array_count_values($emotions);
arsort($freq_emotions);
$most_common_emotion = array_slice(array_keys($freq_emotions), 0, 1, true)[0];

$emotions_encoded = base64_encode($most_common_emotion);

$good_emotions = array('excited', 'joy', 'happy', 'surprise');
$allow_transaction = false;
foreach ($good_emotions as $good_emotion) {
	if (preg_match("@{$good_emotion}@i", $most_common_emotion)) {
		$allow_transaction = true;
		break;
	}
}
if ($allow_transaction) {
	update_status('3', $transaction_id, $emotions_encoded);	
} else {
	update_status('2', $transaction_id, $emotions_encoded);	
}