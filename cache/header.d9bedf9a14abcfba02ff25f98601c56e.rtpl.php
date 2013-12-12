<?php if(!class_exists('raintpl')){exit;}?>    <head>
        <?php if( $style!=null ){ ?>

    	<link rel='stylesheet' href='./<?php echo $style;?>' type='text/css' media='screen' />
        <?php }else{ ?>

		<link rel='stylesheet' href='./css/style.css' type='text/css' media='screen' />
    	<link rel="alternate stylesheet" media="screen" type="text/css" title="Terminal" href="./css/terminal.css" />
    	<link rel="alternate stylesheet" media="screen" type="text/css" title="Zen" href="./css/zen.css" />
        <?php } ?>

	
		<title><?php echo $title;?></title>
	</head>
