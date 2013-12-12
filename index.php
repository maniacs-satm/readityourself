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

            
            <?php
                $publicArticles = Article::findPublicArticle();
                if($publicArticles != null && count($publicArticles) >0) {
            
                    echo "Public articles by not connected users:<ul>";
            
                    foreach ($publicArticles as $article) {
                        echo "<li><a href='readityourself.php?url=".urlencode($article->getUrl())."' title='".$article->getTitle()."'>".$article->getTitle()."</a></li>";
                    }
                    
                    echo "</ul>";
                }
            ?>

            <?php
                    if (Session::isLogged()) {
                        $articles = Article::findArticle($_SESSION['username']);
                        if($articles != null && count($articles) >0) {
            
                            echo "<br>Your articles: <ul>";
    
                            foreach ($articles as $article) {
                                echo "<li><a href='readityourself.php?url=".urlencode($article->getUrl())."' title='".$article->getTitle()."'>".$article->getTitle()."</a></li>";
                            }
                            
                            echo "</ul>";
                        }
                    }
            ?>
    		<?php include ("./CHANGE"); ?>
			<?php include ("./LICENSE"); ?>
		</pre>
	</body>
</html>