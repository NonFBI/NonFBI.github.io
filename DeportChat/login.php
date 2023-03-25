<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email_or_username = $_POST['email_or_username'];
	$password = $_POST['password'];

	$handle = fopen('./signup/users.txt', 'r');
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			$fields = explode('|', trim($line));
			if (($fields[1] === $email_or_username || $fields[0] === $email_or_username) && $fields[2] === $password) {
				$_SESSION['username'] = $fields[0];
				fclose($handle);
				header('Location: /chat/chat.php?id=6c72e4c5ff2e');
				exit();
			}
		}
		fclose($handle);
	}

	echo "<script>alert('Invalid email/username or password. Please try again.')</script>";
	echo "<script>window.location.href = '/login.php'</script>";
}
?>
