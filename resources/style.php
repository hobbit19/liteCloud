<?php
// Create a CSS Header
header("Content-Type: text/css; charset=utf-8");

// Request to display all id installed applications
$q = mysqli_query(Cloud::$mysqli, "SELECT `id` FROM `apps`");

// Output cycle id of applications
while($array = mysqli_fetch_assoc($q))
	
// Outputting the contents of the style parameter
echo Cloud::$application->appoprion(
	$array['id'], 'style', Cloud::$application->init($array['id'], APPLICATION_ID)
);
