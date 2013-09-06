<?php 
// FUNCTIONS BEGIN
require_once dirname(__FILE__).'/inc/includes.php';

if (isset($_GET['logout'])) {
    Session::logout();
    header('Location: index.php');
} else if (isset($_POST['login']) && isset($_POST['password'])
    && Session::login($_POST['login'], $_POST['password'], 'demo', 'demo')) {

    if(Session::isLogged() && $_SESSION['username'] != null && !is_dir('./'.SAVED_PATH .'/'.$_SESSION['username']) ) {
        mkdir('./'.SAVED_PATH .'/'.$_SESSION['username'], 0705);
    }

    header('Location: index.php');
} else {
    raintpl::$tpl_dir = './tpl/'; // template directory
	raintpl::$cache_dir = "./cache/"; // cache directory
	raintpl::$base_url = url(); // base URL of blog
	raintpl::configure( 'path_replace', false );
	raintpl::configure('debug', true); 

	$tpl = new raintpl(); //include Rain TPL

	$tpl->draw( "login"); // draw the template
}
