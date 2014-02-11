<?php 
// FUNCTIONS BEGIN
require_once dirname(__FILE__).'/inc/includes.php';

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<link rel='stylesheet' href='inc/style.css' type='text/css' media='screen' />
		<script src="inc/include.js"></script>
	</head>
	<body>
		<div class="header" class="marginauto">
			<span>
<?php 

    if (!Session::isLogged()) {
        ?><a href="log.php" title="Login page">login page</a><?php
    } else {
        ?>Hello <?php print $_SESSION['username']; ?>, you are logged in. <a href="log.php?logout">Logout</a><?
    }
?>
			</span>
		</div>
		<div class="readityourselfform">
			<span class="form fiftypercent">
				Just put a URL here and type submit, Enjoy ;)
				<form action="./readityourself.php">
					<input type="text" name="url" id="url" maxlength="2048" size="80" /><input type="submit">
				</form>
			</span>
		</div>
		<div class="alreadyread">

            <table id="readityourself" class="marginauto" summary="List of Read It Yourself Pages">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th class="notSortable">Url</th>
                </tr>
            </thead>
            <tbody>
                
            <?php
                $publicArticles = Article::findPublicArticle();
                if($publicArticles != null && count($publicArticles) >0) {
            
                    foreach ($publicArticles as $article) {
                        echo "<tr><th>".$article->getDate()."</th>";
                        echo "<td>".$article->getTitle()."</td>";

                        echo "<td>";
                        echo "<a href='readityourself.php?url=".urlencode($article->getUrl())."' title='".$article->getTitle()."'>Default</a>&nbsp;";
                        echo "<a href='readityourself.php?url=".urlencode($article->getUrl())."&css=zen' title='".$article->getTitle()."'>Zen</a>&nbsp;";
                        echo "<a href='readityourself.php?url=".urlencode($article->getUrl())."&css=terminal' title='".$article->getTitle()."'>Terminal</a>&nbsp;";
                        echo "<a href='readityourself.php?url=".urlencode($article->getUrl())."&css=tinytypo' title='".$article->getTitle()."'>Tinytypo</a>&nbsp;";
                        echo "</td></tr>";
                    }
                    
                }
            ?>

            <?php
                    if (Session::isLogged()) {
                        $articles = Article::findArticle($_SESSION['username']);
                        if($articles != null && count($articles) >0) {
            
                            foreach ($articles as $article) {
                                echo "<tr><th>".$article->getDate()."</th><td><a href='readityourself.php?url=".urlencode($article->getUrl())."' title='".$article->getTitle()."'>".$article->getTitle()."</a></td></tr>";
                            }
                            
                        }
                    }
            ?>
            </tbody>
            </table>
        </div>
        <div class="changelog">
        	<pre class="fiftypercent marginauto">
	        	<code>
	    			<?php include ("./CHANGE"); ?>
				</code>
    		</pre>
		</div>
        <div class="license">
        	<pre class="fiftypercent marginauto">
	        	<code>
					<?php include ("./LICENSE"); ?>
				</code>
    		</pre>
		</div>
		<script>
		    window.onload = function() {
				var sales = new TableSort("readityourself");
			};
		</script>
	</body>
</html>