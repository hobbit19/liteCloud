<?php
// Checking the receipt of the incoming parameter
if (!isset($_GET['get']) || empty($_GET['get'])) return NULL;

// Getting the real path of a file
$file = Guard::slashes(urldecode($_GET['get']));

// Correction of the output path
$file = ($file[0] == '/') ? $CONFIG['path'] . $file : "{$CONFIG['path']}/{$file}";

// Check for the existence of a file
if(!file_exists($file)) return NULL;

// Clearing the cache
if(ob_get_level()) ob_end_clean();

// Issuing headers
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream'); // Stream transmission
header('Content-Disposition: attachment; filename=' . basename($file)); // Output name
header('Content-Transfer-Encoding: binary'); // Encoding type
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file)); // Content length

// File retrieval
readfile($file);
