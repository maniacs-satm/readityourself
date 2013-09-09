<?php 
// FUNCTIONS BEGIN
require_once dirname(__FILE__).'/inc/includes.php';

/*
 
    TODO: penser a ajouter la gestion des utilisateurs et des fichiers sauvegarder via XML:
    http://php.net/manual/en/function.simplexml-load-string.php
 
 */

if (isset($_GET['logout'])) {
    Session::logout();
    header('Location: index.php');
    die();
} else if (isset($_POST['login']) && isset($_POST['password'])) {
    $user = User::getUser('./conf/',$_POST['login']);
    if($user && $user->getPassword() != null && Session::login($_POST['login'], md5($_POST['password']), $user->getLogin(), $user->getPassword())) {
        if(Session::isLogged() && $_SESSION['username'] != null && !is_dir('./'.SAVED_PATH .'/'.$_SESSION['username']) ) {
            mkdir('./'.SAVED_PATH .'/'.$_SESSION['username'], 0705);
        }
    
        header('Location: index.php');
        die();
    }
}

raintpl::$tpl_dir = './tpl/'; // template directory
raintpl::$cache_dir = "./cache/"; // cache directory
raintpl::$base_url = url(); // base URL of blog
raintpl::configure( 'path_replace', false );
raintpl::configure('debug', true); 

$tpl = new raintpl(); //include Rain TPL

$tpl->draw( "login"); // draw the template
