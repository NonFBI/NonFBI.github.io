<?php
// Define the upload directory
$uploadDir = "uploads/";

// Create a unique directory name for the uploaded file
$dirName = uniqid();

// Create a new directory with the unique name
mkdir($uploadDir . $dirName);

// Define the file name and path
$fileName = basename($_FILES["fileToUpload"]["name"]);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$filePath = $uploadDir . $dirName . '/' . $fileName;

// Save the file to the upload directory
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath)) {
    // Define the metadata array
    $metadata = array(
        'filename' => $fileName,
        'type' => $fileType,
        'url' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $uploadDir . $dirName . '/' . $fileName
    );

    // Save the metadata to a JSON file
    file_put_contents($uploadDir . $dirName . '/' . $fileName . '.json', json_encode($metadata));

    // Return the metadata as a JSON response
    echo json_encode($metadata);
} else {
    // Return an error response
    http_response_code(500);
    echo json_encode(array('message' => 'Failed to upload file.'));
}
?>
