<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	if (empty($username) || empty($email) || empty($password)) {
		echo "<script>alert('Please fill out all fields.')</script>";
		echo "<script>window.location.href = '/signup'</script>";
	} else {
		// Read existing users from file
		$users = array();
		$handle = fopen('users.txt', 'r');
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$fields = explode('|', trim($line));
				$users[$fields[0]] = true; // Use username as key for easy lookup
				if ($fields[1] === $email) {
					fclose($handle);
					echo "<script>alert('Email address already in use. Please choose a different email.')</script>";
					echo "<script>window.location.href = '/signup'</script>";
					exit();
				}
			}
			fclose($handle);
		}

		// Check for duplicate username
		if (isset($users[$username])) {
			echo "<script>alert('Username already taken. Please choose a different username.')</script>";
			echo "<script>window.location.href = '/signup'</script>";
			exit();
		}

		// Write new user to file
		$data = $username . '|' . $email . '|' . $password . "\n";
		if (file_put_contents('users.txt', $data, FILE_APPEND) !== false) {
			echo "<script>alert('Signup successful!')</script>";
			echo "<script>window.location.href = '/'</script>";
		} else {
			echo "<script>alert('Signup failed. Please try again later.')</script>";
			echo "<script>window.location.href = '/signup'</script>";
		}
	}
}
?>
