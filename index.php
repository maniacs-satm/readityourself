<?php 
// FUNCTIONS BEGIN
require_once dirname(__FILE__).'/inc/includes.php';

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<link rel='stylesheet' href='static/css/style.css' type='text/css' media='screen' />
		<script src="inc/include.js"></script>

	<script type="application/javascript" src="static/js/jquery.min.js"></script>
	<script type="application/javascript" src="static/js/config.js"></script>
	<script type="application/javascript" src="static/js/skel.min.js"></script>
	<script type="application/javascript" src="static/js/skel-panels.min.js"></script>
	<link media="screen, projection" href="static/css/pygments.css" type="text/css" rel="stylesheet">
	<link media="screen, projection" href="static/css/fonts.css" type="text/css" rel="stylesheet">
	
	<noscript>
		<!-- YUI CSS reset, fonts, base --> 
		<!--[if lte IE 9]> <link rel="stylesheet" type="text/css" href="/static/css/ie9.css" media="screen, projection" /> <![endif]--> 
		<link rel="stylesheet" type="text/css" href="/static/css/skel-noscript.css" media="screen, projection" /> 
		<link rel="stylesheet" type="text/css" href="/static/css/style.css" media="screen, projection" /> 
		<link rel="stylesheet" type="text/css" href="/static/css/style-desktop.css" media="screen, projection" /> 
	</noscript>
	

	</head>
	<body class="subpage">
		<div id="skel-panels-pageWrapper" style="position: relative; left: 0px; right: 0px; top: 0px; backface-visibility: hidden; transition: -moz-transform 0.25s ease-in-out 0s;">
			<div id="header-wrapper">
				<header id="header" class="container">
					<div class="row">
						<div class="12u">
							<h1>
							<a id="logo" href="/">Readt It Yourself</a>
							</h1>
							<h4>You just need to read, no pub, no script !</h4>
							<nav id="nav">
							
							<?php 
							
							    if (!Session::isLogged()) {
							        ?><a href="log.php" title="Login page">login page</a><?php
							    } else {
							        ?>Hello <?php print $_SESSION['username']; ?>, you are logged in. <a href="log.php?logout">Logout</a><?php
							    }
							?>
								<a href="./display.php?page=CHANGE">Change</a>
								<a href="./display.php?page=LICENSE">License</a>
							</nav>
						</div>
					</div>
				</header>
			</div>
			<div id="content-wrapper">
				<div id="content">
					<div class="container">
						<div class="row">
							<div class="9u">
								<section>
									<span class="form fiftypercent">
										Just put a URL here and type submit, Enjoy ;)
										<form action="./readityourself.php">
											<input type="text" name="url" id="url" maxlength="2048" size="80" /><input type="submit">
										</form>
									</span>
                                                                    
                                                                    Or drag and drop this link to your bookmark and <br>
                                                                        click on it on the page you want to <a href="javascript:(function(){var%20url%20=%20location.href;var%20title%20=%20document.title%20||%20url;window.open('<?=url()?>readityourself.php?&url='%20+%20encodeURIComponent(url),'_self');})();" title="Read It Yourself">Read It Yourself</a>
								</section>
							</div>
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="9u">
								<section>
						
						            <table id="readityourself" class="marginauto" summary="List of Read It Yourself Pages">
						            <thead>
						                <tr>
						                    <th>Date</th>
						                    <th>Title</th>
						                </tr>
						            </thead>
						            <tbody>
						                
						            <?php
						            /*
						                $publicArticles = Article::findPublicArticle();
						                if($publicArticles != null && count($publicArticles) >0) {
						            
						                    foreach ($publicArticles as $article) {
						                        echo "<tr><th>".$article->getDate()."</th>";
						                        echo "<td><a href='readityourself.php?url=".urlencode($article->getUrl())."' title='".$article->getTitle()."'>".$article->getTitle()."</a></td>";
						
						                        echo "</tr>";
						                    }
						                    
						                }
						            */
						            ?>
						
						            <?php
						                    if (Session::isLogged()) {
						                        $articles = Article::findArticle($_SESSION['username']);
						                        if($articles != null && count($articles) >0) {
						            
						                            foreach ($articles as $article) {
						                                echo "<tr><th>".$article->getDate()."</th><td><a href='readityourself.php?
						                                url=".urlencode($article->getUrl())."' title='".$article->getTitle()."'>".$article->getTitle()."</a></td></tr>";
						                            }
						                            
						                        }
						                    }
						            ?>
						            </tbody>
						            </table>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="copyright">
				<footer>
					<div>
						&copy; Copyright 2014 - <a href="http://www.memiks.fr/">memiks.fr</a> | <a href="http://shaarli.memiks.fr/">Liens</a> / <a href="http://rss.memiks.fr/">RSS</a> / <a href="http://wiki.memiks.fr/">Wiki</a> / <a href="mailto:&#109;&#101;&#109;&#105;&#107;&#115;&#064;&#109;&#101;&#109;&#105;&#107;&#115;&#046;&#102;&#114;">Contact</a>
						<br>Licence: MIT License
						<br>More information HERE: <a href="http://www.readityourself.net/">http://www.readityourself.net/</a> Version : <span class="version">{$version}</span>
						<a href="https://github.com/memiks/readityourself">sources on github</a> | Design: <a href="http://html5up.net">HTML5 UP</a> | Images: <a href="http://fotogrph.com">fotogrph</a>
					</div>
				</footer>
			</div>
		</div>
		<script>
		    window.onload = function() {
				var sales = new TableSort("readityourself");
			};
		</script>
	</body>
</html>