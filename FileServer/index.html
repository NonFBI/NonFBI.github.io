<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>File Upload</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="container">
        <h1>File Upload</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id="upload-form">
            <input type="file" name="fileToUpload" id="fileToUpload" class="file-input" onchange="uploadFile()">
            <label for="fileToUpload" class="file-label">
                <span class="file-icon"></span> Choose a file
            </label>
        </form>
        <div id="upload-status"></div>
        <div id="progress-container">
            <div id="progress-bar"></div>
        </div>
        <div id="download-link-container" style="display: none">
            <label for="download-link" class="download-link-label">Download Link:</label>
            <input type="text" name="download-link" id="download-link" class="download-link-input" readonly>
            <button onclick="copyToClipboard()">Copy</button>
        </div>
    </div>
    <script>
        function uploadFile() {
            var fileInput = document.getElementById('fileToUpload');
            var file = fileInput.files[0];
            var xhr = new XMLHttpRequest();
            var progressBar = document.getElementById('progress-bar');
            var uploadStatus = document.getElementById('upload-status');
            var downloadLinkContainer = document.getElementById('download-link-container');
            var downloadLink = document.getElementById('download-link');
            var formData = new FormData();
            formData.append('fileToUpload', file);
            xhr.upload.addEventListener('progress', function(event) {
                var percent = (event.loaded / event.total) * 100;
                progressBar.style.width = percent + '%';
            });
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        uploadStatus.textContent = file.name + ' uploaded successfully.';
                        var response = JSON.parse(xhr.responseText);
                        downloadLink.value = response.url;
                        downloadLinkContainer.style.display = 'block';
                    } else {
                        uploadStatus.textContent = 'There was an error uploading ' + file.name + '.';
                    }
                }
            };
            xhr.open('POST', 'upload.php', true);
            xhr.send(formData);
        }

        function copyToClipboard() {
            var copyText = document.getElementById('download-link');
            copyText.select();
            document.execCommand('copy');
            alert('URL copied to clipboard.');
        }
    </script>
</body>
</html>