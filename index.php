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
						                $publicArticles = Article::findPublicArticle();
						                if($publicArticles != null && count($publicArticles) >0) {
						            
						                    foreach ($publicArticles as $article) {
						                        echo "<tr><th>".$article->getDate()."</th>";
						                        echo "<td><a href='readityourself.php?url=".urlencode($article->getUrl())."' title='".$article->getTitle()."'>".$article->getTitle()."</a></td>";
						
						                        echo "</tr>";
						                    }
						                    
						                }
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
					<div class="container">
						<div class="row">
							<div class="9u">
								<section>
						        	<code>
						    			<?php include ("./CHANGE"); ?>
									</code>
								</section>
							</div>
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="9u">
								<section>
						        	<code>
										<?php include ("./LICENSE"); ?>
									</code>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
		    window.onload = function() {
				var sales = new TableSort("readityourself");
			};
		</script>
	</body>
</html>