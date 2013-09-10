<?php 
// FUNCTIONS BEGIN
require_once dirname(__FILE__).'/Session.php';
require_once dirname(__FILE__).'/../class/User.php';
require_once dirname(__FILE__).'/../class/Article.php';
require_once dirname(__FILE__).'/../class/Utils.php';
// Personnalize PHP session name
Session::$sessionName = 'readityourself'; // default is empty
// If the user does not access any page within this time,
// his/her session is considered expired (3600 sec. = 1 hour)
Session::$inactivityTimeout = 7200; // default is 3600
// Ban IP after this many failures.
Session::$banAfter = 5; // default is 4
// File storage for failures and bans. If empty, no ban management.
Session::$banFile = dirname(__FILE__).'/../cache/ipbans.php'; // default is empty

Session::init();

// Set locale to French
setlocale(LC_ALL, 'fr_FR');

// set timezone to Europe/Paris
date_default_timezone_set('Europe/Paris');

// set charset to utf-8 important since all pages will be transform to utf-8
header('Content-Type: text/html;charset=utf-8');

// get readability library
require_once dirname(__FILE__).'/config.php';

// appel de la libraire RainTPL.
require_once dirname(__FILE__).'/rain.tpl.class.php';
