function uploadFile() {
    var fileInput = document.getElementById('fileToUpload');
    var file = fileInput.files[0];
    var xhr = new XMLHttpRequest();
    var progressBar = document.getElementById('progress-bar');
    var uploadStatus = document.getElementById('upload-status');
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
                var link = document.createElement('a');
                link.href = response.url;
                link.textContent = response.url;
                uploadStatus.appendChild(link);
            } else {
                uploadStatus.textContent = 'There was an error uploading ' + file.name + '.';
            }
        }
    };
    xhr.open('POST', 'upload.php', true);
    xhr.send(formData);
}