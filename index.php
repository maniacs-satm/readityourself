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

    		<?php include("./CHANGE"); ?>
			<?php include("./LICENSE"); ?>
							 
		</pre>
	</body>
</html>