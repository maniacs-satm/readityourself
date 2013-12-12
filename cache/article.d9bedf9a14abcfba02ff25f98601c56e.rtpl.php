<?php if(!class_exists('raintpl')){exit;}?><html>
	<?php $tpl = new RainTPL;$tpl->assign( $this->var );$tpl->draw( "header" );?>

	<body>
		<article>
			<h1><a href="<?php echo $url;?>"><?php echo $title;?></a></h1>
			<div id="readityourselfcontent">
				<?php echo $content;?>

			</div>
			<span class="comeFrom">Come From : <a href="<?php echo $url;?>"><?php echo $url;?></a>
		</article>
		<?php $tpl = new RainTPL;$tpl->assign( $this->var );$tpl->draw( "footer" );?>

	</body>
</html>
