<?php
header('Content-type:text/html; charset=utf-8');
// Set locale to French
setlocale(LC_ALL, 'fr_FR');

// set timezone to Europe/Paris
date_default_timezone_set('Europe/Paris');

// set charset to utf-8 important since all pages will be transform to utf-8
header('Content-Type: text/html;charset=utf-8');

// get readability library
require_once dirname(__FILE__).'/inc/Readability.php';

// get Encoding library.
require_once dirname(__FILE__).'/inc/Encoding.php';

// function define to retrieve url content
function get_external_file($url, $timeout) {
	// spoofing FireFox 18.0
	$useragent="Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0";

	if  (in_array  ('curl', get_loaded_extensions())) {	
		// Fetch feed from URL
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		 
		// FeedBurner requires a proper USER-AGENT...
		curl_setopt($curl, CURL_HTTP_VERSION_1_1, true);
		curl_setopt($curl, CURLOPT_ENCODING, "gzip, deflate");
		curl_setopt($curl, CURLOPT_USERAGENT, $useragent);

		$data = curl_exec($curl);
		
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		$httpcodeOK = isset($httpcode) and ($httpcode == 200 or $httpcode == 301);
		
		curl_close($curl);
	} else {

		// create http context and add timeout and user-agent
		$context = stream_context_create(array('http'=>array('timeout' => $timeout, // Timeout : time until we stop waiting for the response.
																					'header'=> "User-Agent: ".$useragent, // spoot Mozilla Firefox
																					'follow_location' => true
														)));

		// only download page lesser than 4MB
		$data = @file_get_contents($url, false, $context, -1, 4000000); // We download at most 4 MB from source.
		//	echo "<pre>http_response_header : ".print_r($http_response_header);
		
		if(isset($http_response_header) and isset($http_response_header[0])) {
			$httpcodeOK = isset($http_response_header) and isset($http_response_header[0]) and ((strpos($http_response_header[0], '200 OK') !== FALSE) or (strpos($http_response_header[0], '301 Moved Permanently') !== FALSE));
		}
	}

	// if response is not empty and response is OK
	if (isset($data) and isset($httpcodeOK) and httpcodeOK ) {

		// take charset of page and get it
		preg_match('#<meta .*charset=.*>#Usi', $data, $meta);

		// if meta tag is found
		if (!empty($meta[0])) {
			// retrieve encoding in $enc
			preg_match('#charset="?(.*)"#si', $meta[0], $enc);

			// if charset is found set it otherwise, set it to utf-8
			$html_charset = (!empty($enc[1])) ? strtolower($enc[1]) : 'utf-8';

		} else { 
			$html_charset = 'utf-8';
			$enc[1] = '';
		}

		// replace charset of url to charset of page
		$data = str_replace('charset='.$enc[1], 'charset='.$html_charset, $data);

		return $data;
	}
	else {
		return FALSE;
	}
}

if(isset($_GET['url']) && $_GET['url'] != null && trim($_GET['url']) != "") {
	// get url link
	if(strlen(trim($_GET['url'])) > 2048) {
		echo "Error URL is too large !!";
	} else {
		$url = trim($_GET['url']);

		// decode it
		$url = html_entity_decode($url);
		
		// if url use https protocol change it to http
		if (!preg_match('!^https?://!i', $url)) $url = 'http://'.$url;
		
		// convert page to utf-8
		$html = Encoding::toUTF8(get_external_file($url,15));
		
		if(isset($html) and strlen($html) > 0) {
		
			// send result to readability library
			$r = new Readability($html, $url);

			if($r->init()) {
				// return innerhtml of article found
				echo "<html><head><link rel='stylesheet' href='./css/reset.css' type='text/css' media='all' /><link rel='stylesheet' href='./css/typography.css' type='text/css' media='all' />";
				echo "<title>".$r->articleTitle->innerHTML."</title>";
				echo "</head><body><h1><a href='".$r->url."'>".$r->articleTitle->innerHTML."</a></h1>";
				echo $r->articleContent->innerHTML;
				echo "<br/><br/>Come From : <a href='".$r->url."'>".$r->url."</a>";
				echo "</body></html>";
			} else {
				// return data into an iframe
				echo "<iframe id='readabilityframe'>".$html."</iframe>";
			}
		} else {
			echo "Error unable to get link : ".$url;
		}
	}
}
?>
