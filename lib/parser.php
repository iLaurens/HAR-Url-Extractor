<?php
/*
 * HAR Url Extractor - Extracts object urls from HAR files.
 *
 * parser.php - Main parser class
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

class HAR_Url_Extractor {

	static function Extract_Urls($raw_data) {

		// Check $raw_data
		if(empty($raw_data)) {
			throw new Exception('Empty input data');
		}

		// Decode JSON
		$decoded = json_decode($raw_data);
		if( ($decoded == NULL) || ($decoded === FALSE) ) {
			throw new Exception('Error decoding JSON');
		}

		// Verify data
		if( !isset($decoded->log) || !isset($decoded->log->entries) || !is_array($decoded->log->entries) ) {
			throw new Exception('Decoded data has incorrect format');
		}

		// Initialize vars
		$urls = array();
		$i = 0;

		// Extract urls
		foreach($decoded->log->entries as $entry) {
			$url = $entry->request->url;
			$domain = preg_replace('/^https?:\/\/([^\/]+)\/?.*$/', '\1', $url);
			$urls[$domain . '_' . $i] = $url;
			++$i;
		}

		// How many urls?
		if( $i == 0 ) {
			throw new Exception('No urls found');
		}

		// Sort by domain in alphabetical order
		sort($urls);

		// Return as array
		return $urls;

	}

};

