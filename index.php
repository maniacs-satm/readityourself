<?php 
// FUNCTIONS BEGIN
require_once dirname(__FILE__).'/inc/includes.php';

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	</head>
	<body>
<?php 

    if (!Session::isLogged()) {
        ?><a href="log.php" title="Login page">login page</a><?php
    } else {
        ?>Hello <?php print $_SESSION['username']; ?>, you are logged in. <a href="log.php?logout">Logout</a><?
    }
?>
		<pre>
			Just put a URL here and type submit, Enjoy ;)
			<form action="./readityourself.php">
				<input type="text" name="url" id="url" maxlength="2048" size="80" /><input type="submit">
			</form>

    		<?php echo file_get_contents("./CHANGE"); ?>
			<?php echo file_get_contents("./LICENSE"); ?>
							 
		</pre>
	</body>
</html>