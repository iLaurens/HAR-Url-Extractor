<?php
// Half-assed file validity check
if(!isset($_FILES['f']['tmp_name']) || !file_exists($_FILES['f']['tmp_name'])) {
	echo "Invalid file u tard!";
	die();
}

// Get and decode file
$filename = $_FILES['f']['tmp_name'];
$file = file_get_contents($filename);
$data = json_decode($file);

// Slaughter the tempfile
if(file_exists($filename)) unlink($filename);

// Check decoded data
if( !isset($data->log) || !isset($data->log->entries) || !is_array($data->log->entries) ) {
	echo "File is f00ked!";
	die();
}

// Initialize vars
$urls = array();
$i=0;

// Loop the data and save in array
foreach($data->log->entries as $entry) {
	$url = $entry->request->url;
	$domain = preg_replace('/^https?:\/\/(.*)/', '\1', $url);
	$domain = preg_replace('/([^\/]+)\/.*$/', '\1', $domain);
	$urls[$domain . '_' . $i] = $url;
	++$i;
}

// Sort the data
sort($urls);

// Output it
header("Content-Type: text/plain; charset=utf-8\n");
echo "Below is all external URLs found in HAR file, sorted by domain name in alphabetical order.\n\n\n\n";
print_r($urls);
