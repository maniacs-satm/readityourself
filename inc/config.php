<?php
define("VERSION", "0.0.7");

define ('SAVED_PATH', 'saved/');
define ('TMP_PATH', 'tmp/');
define ('IMAGES_PATH', 'images/');

if(!is_dir('./'.SAVED_PATH)) {
    mkdir('./'.SAVED_PATH, 0705);
}

if(!is_dir('./'.TMP_PATH)) {
    mkdir('./'.TMP_PATH, 0705);
}

$gets = array('picdown','picb64','css','url','logout');

$PICTURES_DOWNLOAD = false;
$PICTURES_BASE64 = true;
$CSS_STYLE = null;

// Annulation de la fonction magic_quotes_gpc.
function strip_magic_quotes(&$valeur)
{
    $valeur = stripslashes($valeur);
}
if (get_magic_quotes_gpc())
{
	array_walk_recursive($_GET, 'strip_magic_quotes');
	array_walk_recursive($_POST, 'strip_magic_quotes');
	array_walk_recursive($_COOKIE, 'strip_magic_quotes');
	array_walk_recursive($_REQUEST, 'strip_magic_quotes');
}

// Désactivation de la fonction magic_quotes_runtime.
if (get_magic_quotes_runtime() && function_exists('set_magic_quotes_runtime'))
{
	set_magic_quotes_runtime(0);
}

// Supression de tout paramètre GET non utilisé.
$gets = (isset($gets)) ? $gets : array('q');
foreach ($_GET as $name => $value)
{
	if (!in_array($name, $gets))
	{
		unset($_GET[$name]);
	}
}
unset($_REQUEST);

function url(){
  $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
  return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

