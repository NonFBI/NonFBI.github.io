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

		$chatrooms_file = 'chatrooms.json';
		$chatrooms = array();
		if (file_exists($chatrooms_file)) {
			$chatrooms = json_decode(file_get_contents($chatrooms_file), true);
		}
		$chatrooms[] = array(
			'id' => $new_chatroom_id,
			'name' => $_POST['chatroom_name'],
			'public' => isset($_POST['public']),
			'creator' => $_SESSION['username']
		);
		file_put_contents($chatrooms_file, json_encode($chatrooms));

		header("Location: /chat/chat.php?id=$new_chatroom_id");
		exit();
	}
}

$chatrooms_file = 'chatrooms.json';
$chatrooms = array();
if (file_exists($chatrooms_file)) {
	$chatrooms = json_decode(file_get_contents($chatrooms_file), true);
}

function get_chatroom_users($chatroom_id) {
	$users_file = "./signup/users-$chatroom_id.txt";
	$users = array();
	if (file_exists($users_file)) {
		$users = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}
	return $users;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Chatrooms</title>
	<link rel="stylesheet" type="text/css" href="/chat/style.css">
</head>
<div class="sidebar">
  <h1>Chatrooms</h1>
  <ul class="chatroom-list">
    <?php foreach ($chatrooms as $chatroom): ?>
      <li class="chatroom-item <?php echo ($chatroom['id'] == $chatroom_id) ? 'selected' : '' ?>">
        <a href="/chat/chat.php?id=<?= $chatroom['id'] ?>">
          <div class="chatroom-name"><?= $chatroom['name'] ?></div>
          <div class="chatroom-type"><?= $chatroom['public'] ? 'Public' : 'Private' ?></div>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
  <div class="create-chatroom">
    <form method="POST">
      <label for="chatroom_name">Create a new chatroom:</label>
      <input type="text" name="chatroom_name" id="chatroom_name" placeholder="Enter a name">
      <div class="chatroom-type">
        <input type="checkbox" name="public" id="public" checked>
        <label for="public">Public</label>
      </div>
      <button type="submit">Create</button>
    </form>
  </div>
</div>
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
			<form method="POST">
				<label for="message">Message:</label>
				<input type="text" name="message" id="message">
				<button type="submit">Send</button>
			</form>
		</div>
	</div>
</body>
</html>