<?php
require('../config.php');

function send_push_notification_android($receiver_id, $message) {
	$url = 'https://fcm.googleapis.com/fcm/send';
	$post = array(
			'to' => $receiver_id,
			'notification' => array(
					"body" => $message,
					"title" => "Title",
					"icon" => "myicon"
			)
	);
	
	$post = json_encode($post);
	
	$headers = array (
			'Authorization: key=' . ANDROID_PUSH_SERVER_KEY,
			'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	
	$result = curl_exec($ch);
	
	curl_close($ch);
	
	return $result;
}

var_dump(
	send_push_notification_android(
		ANDROID_DEVICE_ID,
		'WTF IS NICK TALKING ABOUT'
	)
);