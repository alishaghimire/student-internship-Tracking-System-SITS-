<?php
$filename = basename($_GET['file'] ?? '');
$allowed_extensions = ['pdf', 'doc', 'docx'];

if (!$filename || !preg_match('/^[a-zA-Z0-9_\-]+\.(pdf|docx|doc)$/', $filename)) {
    die("Invalid file name.");
}

// Adjusted path: go up one level from Company/ to reach /uploads/
$filepath = realpath(__DIR__ . '/../uploads/' . $filename);

if (!file_exists($filepath)) {
    http_response_code(404);
    die("File not found.");
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;