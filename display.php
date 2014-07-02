<?php 
// FUNCTIONS BEGIN
require_once dirname(__FILE__).'/inc/includes.php';

/*
 
    TODO: penser a ajouter la gestion des utilisateurs et des fichiers sauvegarder via XML:
    http://php.net/manual/en/function.simplexml-load-string.php
 
 */

if(!isset($_GET['page']) || $_GET['page'] != "CHANGE" && $_GET['page'] != "LICENSE") {
    print "blabla";
    print_r($_GET);
    die;
}

$content = file_get_contents ($_GET['page']);

$content = str_replace ("{{{year}}}", date("Y"), $content);
$content = str_replace ("{{{fullname}}}", "Mémîks", $content);
$content = str_replace ("\n", "<br>", $content);

raintpl::$tpl_dir = './tpl/'; // template directory
raintpl::$cache_dir = "./cache/"; // cache directory
raintpl::$base_url = url(); // base URL of blog
raintpl::configure( 'path_replace', false );
raintpl::configure('debug', true); 

$tpl = new raintpl(); //include Rain TPL


$tpl->assign( "title", $_GET['page']);
$tpl->assign( "content", $content);


    $tpl->assign( "isLogged", Session::isLogged());
    if (Session::isLogged()) {
        $tpl->assign( "username", $_SESSION['username']);
        $tpl->assign( "logpage", "./log.php?logout");
        $tpl->assign( "logname", "Logout");
    } else {
        $tpl->assign( "logpage", "./log.php");
        $tpl->assign( "logname", "Login");
    }



$tpl->draw( "display"); // draw the template
