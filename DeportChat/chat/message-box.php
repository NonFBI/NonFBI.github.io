<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('Location: /chat/login.php');
    exit();
}

$chatroom_id = isset($_GET['id']) ? $_GET['id'] : '';
$chatroom_filename = "chat-$chatroom_id.txt";

$last_updated_time = isset($_GET['time']) ? $_GET['time'] : 0;
$last_updated_size = isset($_GET['size']) ? $_GET['size'] : 0;

if (file_exists($chatroom_filename)) {
    clearstatcache(true, $chatroom_filename);
    $current_size = filesize($chatroom_filename);
    $current_modified_time = filemtime($chatroom_filename);
    if ($current_size > $last_updated_size || $current_modified_time > $last_updated_time) {
        $messages = array();
        $lines = file($chatroom_filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $message = json_decode($line, true);
            $messages[] = $message;
        }
        $response = array(
            'success' => true,
            'messages' => $messages,
            'size' => $current_size,
            'time' => $current_modified_time
        );
        echo json_encode($response);
        exit();
    }
}

$response = array(
    'success' => true,
    'size' => $last_updated_size,
    'time' => $last_updated_time
);
echo json_encode($response);
?>

<div id="chat-form">
  <form method="POST">
    <input type="text" name="message" id="message-box" value="" autocomplete="off" autofocus>
    <button type="submit">Send</button>
  </form>
</div>
<div class="content">
    <h1><?= $chatroom_name ?></h1>
    <div id="chat-messages">
        
    </div>
    <?php include_once('message-box.php'); ?>
</div>
<script>
  var last_updated_time = <?php echo time() ?>;
  var last_updated_size = <?php echo $current_size ?>;
  var chatroom_id = '<?php echo $chatroom_id ?>';

  function updateMessages() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var response = JSON.parse(this.responseText);
        if (response.success) {
          last_updated_time = response.time;
          last_updated_size = response.size;
          var messages = response.messages;
          var messageBox = document.getElementById("message-box");
          var messageText = messageBox.value;
          var chatMessages = document.getElementById("chat-messages");
          chatMessages.innerHTML = '';
          messages.forEach(function(message) {
            var div = document.createElement('div');
            div.className = 'chat-message';
            var strong = document.createElement('strong');
            strong.innerText = message.username + ': ';
            var text = document.createTextNode(message.message);
            div.appendChild(strong);
            div.appendChild(text);
            chatMessages.appendChild(div);
          });
          messageBox.focus();
          messageBox.value = messageText;
        }
      }
    };
    request.open('GET', '/chat/message-box.php?id=' + chatroom_id + '&time=' + last_updated_time + '&size=' + last_updated_size);
    request.send();
  }

  setInterval(updateMessages, 1000);
</script>