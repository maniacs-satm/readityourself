<?php

// FUNCTIONS BEGIN
require_once dirname(__FILE__) . '/inc/includes.php';

// get readability library
require_once dirname(__FILE__) . '/inc/Readability.php';

// get Encoding library.
require_once dirname(__FILE__) . '/inc/Encoding.php';

// FUNCTIONS BEGIN
if (isset($_GET['picdown']) && $_GET['picdown'] != null) {
    $PICTURES_DOWNLOAD = $_GET['picdown'] == 'true';
}

if (isset($_GET['picb64']) && $_GET['picb64'] != null) {
    $PICTURES_BASE64 = $_GET['picb64'] == 'true';
}

if (isset($_GET['css']) && $_GET['css'] != null && file_exists('css/' . $_GET['css'] . '.css')) {
    $CSS_STYLE = 'css/' . $_GET['css'] . '.css';
}

function generate_page($url, $title, $content) {
    global $CSS_STYLE;

    raintpl::$tpl_dir = './tpl/'; // template directory
    raintpl::$cache_dir = "./cache/"; // cache directory
    raintpl::$base_url = url(); // base URL of blog
    raintpl::configure('path_replace', false);
    raintpl::configure('debug', true);

    $tpl = new raintpl(); //include Rain TPL

    $tpl->assign("url", $url);
    $tpl->assign("tinyurl", md5($url));
    $tpl->assign("title", $title);

    $tpl->assign("isLogged", Session::isLogged());
    if (Session::isLogged()) {
        $tpl->assign("username", $_SESSION['username']);
        $tpl->assign("logpage", "./log.php?logout");
        $tpl->assign("logname", "Logout");
    } else {
        $tpl->assign("logpage", "./log.php");
        $tpl->assign("logname", "Login");
    }

    $tpl->assign("content", $content);
    $tpl->assign("version", VERSION);

    $tpl->draw("article"); // draw the template
}

if (isset($_GET['url']) && $_GET['url'] != null && trim($_GET['url']) != "") {
    // get url link
    if (strlen(trim($_GET['url'])) > 2048) {
        echo "Error URL is too large !!";
    } else {
        $url = trim($_GET['url']);

        if (!Utils::isValidMd5($url)) {
            // decode it
            $url = html_entity_decode($url);

            // if url use https protocol change it to http
            if (!preg_match('!^https?://!i', $url))
                $url = 'http://' . $url;
        }

        $article = new Article;
        $article->setUrl($url);

        if (!$article->isAlreadyExists()) {
            if ($article->retrieveContent()) {
                if ($article->readiIt(isset($_GET['debug']))) {
                    //$article->modifyContent();
                    //$article->saveContent();
                }
            }
        } else {
            $article = Article::getArticle($url);
            // only for debug
            if ($article->readiIt(isset($_GET['debug']))) {
                //$article->modifyContent();
                $article->saveContent();
            }
        }

        if ($article && $article->isLoaded()) {
            generate_page($article->getUrl(), $article->getTitle(), $article->getFinalContent());
            //generate_page($url,$r->articleTitle->innerHTML,$r->articleContent->innerHTML);
        } else {
            echo "Error unable to get link : " . $url;
        }
    }
}