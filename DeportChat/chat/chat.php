<?php
session_start();

if (!isset($_SESSION['username'])) {
	header('Location: /chat/login.php');
	exit();
}

$chatroom_id = isset($_GET['id']) ? $_GET['id'] : '';
$chatroom_name = '';
$chatroom_type = '';
$messages = array();
if (!empty($chatroom_id)) {
	$chatrooms_file = 'chatrooms.json';
	$chatrooms = array();
	if (file_exists($chatrooms_file)) {
		$chatrooms = json_decode(file_get_contents($chatrooms_file), true);
	}
	foreach ($chatrooms as $chatroom) {
		if ($chatroom['id'] == $chatroom_id) {
			$chatroom_name = $chatroom['name'];
			$chatroom_type = isset($chatroom['type']) ? $chatroom['type'] : 'public';
			$chatroom_filename = $chatroom['filename'];
			$messages = get_messages($chatroom_filename);
			break;
		}
	}

	if (empty($chatroom_name)) {
		header("Location: /chat/chatrooms.php");
		exit();
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!empty($_POST['message'])) {
		add_message($chatroom_filename, $_SESSION['username'], $_POST['message']);
		$_SESSION['message_text'] = $_POST['message'];
		header("Location: /chat/chat.php?id=$chatroom_id");
		exit();
	}
}

function get_messages($filename) {
	$messages = array();
	if (!empty($filename) && file_exists($filename)) {
		$lines = file($filename, FILE_IGNORE_NEW_LINES);
		foreach ($lines as $line) {
			$message = json_decode($line, true);
			$messages[] = $message;
		}
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

function get_chatroom_users($chatroom_id) {
	$users_file = "./signup/users-$chatroom_id.txt";
	$users = array();
	if (file_exists($users_file)) {
		$users = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}
	return $users;
}

$chatroom_users = get_chatroom_users($chatroom_id);
$message_text = isset($_SESSION['message_text']) ? $_SESSION['message_text'] : '';

?>
<!DOCTYPE html>
<html>
<head>
	<title>Chatroom - <?php echo htmlspecialchars($chatroom_name); ?></title>
	<link rel="stylesheet" type="text/css" href="/chat/style.css">
	<meta http-equiv="refresh" content="10">
</head>
<body>
	<div class="container">
		<div class="sidebar">
			<h1>Chatrooms</h1>
			<?php foreach ($chatrooms as $chatroom): ?>
				<a href="/chat/chat.php?id=<?= $chatroom['id'] ?>" class="<?php echo ($chatroom['id'] == $chatroom_id) ? 'selected' : '' ?>"><?= $chatroom['name'] ?></a>
			<?php endforeach; ?>
			<form method="POST">
				<label for="chatroom_name">Create a new chatroom:</label>
				<input type="text" name="chatroom_name" id="chatroom_name" placeholder="Enter a name">
				<button type="submit">Create</button>
			</form>
		</div>
		<div class="content">
			<h1><?= $chatroom_name ?></h1>
			<div id="chat-messages">
				<?php foreach ($messages as $message): ?>
					<div class="chat-message">
						<strong><?= htmlspecialchars($message['username']) ?>:</strong> <?= htmlspecialchars($message['message']) ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div id="message-box">
				<form method="POST" id="message-form">
					<input type="hidden" name="last_updated_size" id="last_updated_size" value="<?= $last_updated_size ?>">
					<input type="hidden" name="last_updated_time" id="last_updated_time" value="<?= $last_updated_time ?>">
					<textarea name="message" id="message" placeholder="Type a message..."><?= htmlspecialchars($message_text) ?></textarea>
					<button type="submit">Send</button>
				</form>
			</div>
		</div>
	</div>
	<script src="/chat/script.js"></script>
</body>
</html>