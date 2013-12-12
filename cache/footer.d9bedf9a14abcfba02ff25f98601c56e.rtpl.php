<?php if(!class_exists('raintpl')){exit;}?><footer>
	<div>  
	Copyright &copy; <a href="http://www.memiks.fr/">memiks.fr</a> | <a href="http://shaarli.memiks.fr/">Liens</a> / <a href="http://rss.memiks.fr/">RSS</a> / <a href="http://wiki.memiks.fr/">Wiki</a>  / <a href="mailto:&#109;&#101;&#109;&#105;&#107;&#115;&#064;&#109;&#101;&#109;&#105;&#107;&#115;&#046;&#102;&#114;">Contact</a><br>
			Licence: WTF Licence<br>
			More information HERE: <a href="http://www.readityourself.net/">http://www.readityourself.net/</a> Version : <span class="version"><?php echo $version;?></span>
            <a href="https://github.com/memiks/readityourself">sources on github</a><br>
        
            <?php if( $isLogged ){ ?>

                Hello <?php echo $username;?>, you are logged in. <a href="<?php echo $logpage;?>?logout">Logout</a>
            <?php }else{ ?>

                <a href="<?php echo $logpage;?>" title="Login page">login page</a>
            <?php } ?>

	</div>
</footer>
