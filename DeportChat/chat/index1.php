<?php
session_start();

if (!isset($_SESSION['username'])) {
	header('Location: /chat/login.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_POST['chatroom_name'])) {
		$new_chatroom_id = bin2hex(random_bytes(6));
		$new_chatroom_filename = "chat-$new_chatroom_id.txt";
		file_put_contents($new_chatroom_filename, '');

		header("Location: /chat/chat.php?id=$new_chatroom_id");
		exit();
	}
}

$chatroom_id = isset($_GET['id']) ? $_GET['id'] : '';
$chatroom_filename = "chat-$chatroom_id.txt";
if (!file_exists($chatroom_filename)) {
	header("Location: /chat/chat.php?id=globalchat");
	exit();
}

$_SESSION['last_visited_chatroom_id'] = $chatroom_id;

function get_messages($filename) {
	$messages = array();
	$lines = file($filename, FILE_IGNORE_NEW_LINES);
	foreach ($lines as $line) {
		$message = json_decode($line, true);
		$messages[] = $message;
	}
	return $messages;
}

function add_message($filename, $username, $message) {
	$message = array(
		'username' => $username,
		'timestamp' => time(),
		'message' => $message
	);
	file_put_contents($filename, json_encode($message) . "\n", FILE_APPEND);
}

$messages = get_messages($chatroom_filename);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_POST['message'])) {
		add_message($chatroom_filename, $_SESSION['username'], $_POST['message']);
		header("Location: /chat/chat.php?id=$chatroom_id");
		exit();
	}
}
