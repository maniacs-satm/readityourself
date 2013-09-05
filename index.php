<?php
function url(){
  $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
  return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	</head>
	<body>
		<pre>
			Just put a URL here and type submit, Enjoy ;)
			<form action="./readityourself.php">
				<input type="text" name="url" id="url" maxlength="2048" size="80" /><input type="submit">
			</form>
				CHANGE: 
					2013-03-01 : 
						* use cURL if php extension is present, otherwise use file_get_contents
						* now ReadItYourself can handle URL forward
						* Add a Bookmarklet for <a href="javascript:(function(){var%20url%20=%20location.href;var%20title%20=%20document.title%20||%20url;window.open('<? echo url()?>/readityourself.php?url='%20+%20encodeURIComponent(url),'_self');})();" title="ReadItYourself">ReadItYourself</a> Coded by Tristan Velter for Read It Yourself (thanks ;) ) <a href="http://velter.org/">http://velter.org/</a>
						* use RainTPL to render html page
						* add footer to put licence, where to find information about ReadItYourself and version number


			<?php include("./LICENSE"); ?>
							 
		</pre>
	</body>
</html>