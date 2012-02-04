#!/usr/bin/php
<?php
/*
 * HAR Url Extractor - Extracts object urls from HAR files.
 *
 * cli.php - Usage example for CLI
 *
 * Copyright (c) 2012, Johan Hedberg <mail@johan.pp.se>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Johan Hedberg nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

// Load parser class
require "../lib/parser.php";

// Is CLI?
if(PHP_SAPI !== 'cli') {
	die("Not running via CLI, this script only supports CLI!\n");
	exit(1);
}

// Define usage function
function my_usage() {
	echo "Usage: cli.php <filename.har>\n";
	exit(1);
}

// Check argument
if( !is_array($argv) || !isset($argv[1]) || empty($argv[1]) ) {
	my_usage();
}
$filename = $argv[1];
if ( !file_exists($filename) || !is_readable($filename) ) {
	die("File does not exist, or isn't readable!\n");
	exit(1);
}

// Get data
$data = file_get_contents($filename);
if(empty($data)) {
	die("File empty!\n");
}

// Parse out urls
try {
	$urls = HAR_Url_Extractor::Extract_Urls($data);
}
catch (Exception $e) {
	echo "Caught exception: " . $e->getMessage() . "\n";
	exit(1);
}

// Print urls
echo "Below are the object urls found in HAR file.\n\n\n";
print_r($urls);
