<?php

class Article {

    private $url = false;
    private $original;
    private $date;
    private $title;
    private $readIt;
    private $finalContent;
    private $loaded = false;

    public function getUrl() {
        return base64_decode($this->url);
    }

    public function setUrl($url) {
        $this->url = base64_encode($url);
    }

    public function getOriginal() {
        return base64_decode($this->original);
    }

    public function setOriginal($original) {
        $this->original = base64_encode($original);
    }

    public function getDate() {
        if (!isset($this->date)) {
            if ($this->isAlreadyExists()) {
                $this->setDate(date("Y-m-d", filemtime(Utils::create_assets_directory($this->getUrl()) . '/article.ser')));
            } else {
                $this->setDate(date("Y-m-d"));
            }
        }
        return $this->date;
    }

    public function setDate($date) {

        $this->date = $date;
    }

    public function getFinalContent() {
        return base64_decode($this->finalContent);
    }

    public function setFinalContent($finalContent) {
        $this->finalContent = base64_encode($finalContent);
    }

    public function getTitle() {
        return base64_decode($this->title);
    }

    public function setTitle($title) {
        $this->title = base64_encode($title);
    }

    public function isLoaded() {
        return $this->loaded;
    }

    public function saveContent() {
        $directory = Utils::create_assets_directory($this->getUrl());
        if (is_dir($directory)) {
            $article = serialize($this);
            // save article in directory
            file_put_contents($directory . '/article.ser', $article);
        } else {
            return false;
        }
    }

    public function isAlreadyExists() {
        $article_file = Utils::create_assets_directory($this->getUrl()) . '/article.ser';
        return file_exists($article_file);
    }

    public static function getArticle($url) {
        $article_file = Utils::create_assets_directory($url) . '/article.ser';
        if (file_exists($article_file)) {
            // read article from file
            return unserialize(file_get_contents($article_file));
        } else {
            return false;
        }
    }

    public static function getArticleFromFile($article_file) {
        if (file_exists($article_file)) {
            // read article from file
            return unserialize(file_get_contents($article_file));
        } else {
            return false;
        }
    }

    public function readiIt($debug) {
        // send result to readability library
        $this->readIt = new Readityourself($this->getUrl(),$debug);
        $this->loaded = $this->readIt->init();
        if($this->loaded) {
            $this->setTitle($this->readIt->articleTitle->innerHTML);
            $this->setFinalContent($this->readIt->articleContent->innerHTML);
            $this->setDate(date("Y-m-d"));
        }
        return $this->loaded;
    }

    public static function findArticle($pattern) {
        $items = glob("./" . SAVED_PATH . $pattern . "/*/article.ser");
        $articles = array();
        foreach ($items as $item) {
            $article = Article::getArticleFromFile($item);
            array_push($articles, $article);
        }
        return $articles;
    }

}
