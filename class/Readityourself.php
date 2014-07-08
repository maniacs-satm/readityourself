<?php

class Readityourself extends Readability {

    private $stripColonIntitle = false;
    private $reduceTitleLength = false;

    /**
     * Get the article title as an H1.
     *
     * @return DOMElement
     *
     * All of the regular expressions in use within readability.
     * Defined up here so we don't instantiate them repeatedly in loops.
     * */
    public $regexps = array(
        'unlikelyCandidates' => '/list-operations-dialog|modal-body|combx|community|comment|disqus|extra|foot|header|menu|remark|rss|shoutbox|sidebar|sponsor|ad-break|agegate|pagination|pager|popup|debutDefinition_|sms_codes/i',
        'okMaybeItsACandidate' => '/tweet|permalink-tweet|js-actionable-user|js-actionable-tweet|js-original-tweet|and|post|article|body|column|main|shadow|js-tweet-text|tweet-text/i',
        'positive' => '/tweet|permalink-tweet|js-actionable-user|js-actionable-tweet|js-original-tweet|article|content|comic|body|content|entry|hentry|main|page|media|attachment|pagination|post|text|blog|story/i',
        'negative' => '/list-operations-dialog|modal-body|combx|com-|contact|comment|foot|footer|_nav|footnote|masthead|meta|outbrain|promo|related|scroll|shoutbox|sidebar|sponsor|shopping|tags|tool|widget|modal-container|sms_codes/i',
        'htmlPositiveTag' => '/content|article|media|video/',
        'htmlNegativeTag' => '/footer|header|button|link|font|meta/',
        'divToPElements' => '/<(td|tr|a|blockquote|dl|div|img|ol|p|pre|table|ul|code|link|font|meta)/i',
        'replaceBrs' => '/(<br[^>]*>[ \n\r\t]*){2,}/i',
        'replaceFonts' => '/<(\/?)font[^>]*>/i',
        'allowedTag' => '<ul><li><figure><h1><h2><h3><h4><a><img><article><p><pre><code><blockquote><xmp><video><embed>',
        // 'trimRe' => '/^\s+|\s+$/g', // PHP has trim()
        'normalize' => '/\s{2,}/',
        'killBreaks' => '/(<br\s*\/?>(\s|&nbsp;?)*){1,}/',
        'video' => '!//(player\.|www\.)?(youtube|vimeo|viddler)\.com!i',
        'skipFootnoteLink' => '/^\s*(\[?[a-z0-9]{1,2}\]?|^|edit|citation needed)\s*$/i'
    );

    /**
     * Create instance of Readability
     * @param string UTF-8 encoded string
     * @param string (optional) URL associated with HTML (used for footnotes)
     * @param string which parser to use for turning raw HTML into a DOMDocument (either 'libxml' or 'html5lib')
     */
    function __construct($url = null, $debug = false) {
        $html = Readityourself::retrieveContent($url);
        parent::__construct($html, $url, null);

        $this->debug = $debug;
    }

    /**
     * Get the article title as an H1.
     *
     * @return DOMElement
     * 
     * Fixes #1 by remove splt ior ":" in title text
     */
    protected function getArticleTitle() {
        $curTitle = '';
        $origTitle = '';

        try {
            $curTitle = $origTitle = $this->getInnerText($this->dom->getElementsByTagName('title')->item(0));
        } catch (Exception $e) {
            
        }

        if (preg_match('/ [\|\-] /', $curTitle)) {
            $curTitle = preg_replace('/(.*)[\|\-] .*/i', '$1', $origTitle);

            if (count(explode(' ', $curTitle)) < 3) {
                $curTitle = preg_replace('/[^\|\-]*[\|\-](.*)/i', '$1', $origTitle);
            }
        }

        // remove to correct split of title with ':' see https://github.com/memiks/readityourself/issues/1
        else if ($this->stripColonIntitle == true && strpos($curTitle, ': ') !== false) {
            $curTitle = preg_replace('/.*:(.*)/i', '$1', $origTitle);

            if (count(explode(' ', $curTitle)) < 3) {
                $curTitle = preg_replace('/[^:]*[:](.*)/i', '$1', $origTitle);
            }
        }


        if (count(explode(' ', trim($curTitle))) <= 4) {
            $curTitle = $origTitle;
        } else if ($this->reduceTitleLength == true && ( strlen($curTitle) > 150 || strlen($curTitle) < 15)) {
            $hOnes = $this->dom->getElementsByTagName('h1');
            if ($hOnes->length == 1) {
                $curTitle = $this->getInnerText($hOnes->item(0));
            }
        }

        $curTitle = trim($curTitle);

        $articleTitle = $this->dom->createElement('h1');
        $articleTitle->innerHTML = trim($curTitle);

        return $articleTitle;
    }

    /**
     * Get an elements class/id weight. Uses regular expressions to tell if this 
     * element looks good or bad.
     *
     * @param DOMElement $e
     * @return number (Integer)
     */
    public function getClassWeight($e) {
        if (!$this->flagIsActive(self::FLAG_WEIGHT_CLASSES)) {
            return 0;
        }

        $weight = 0;

        if (preg_match($this->regexps['negative'], $e->tagName)) {
            $weight -= 250;
        }
        if (preg_match($this->regexps['positive'], $e->tagName)) {
            $weight += 250;
        }

        /* Look for a special ID */
        if ($e->hasAttribute('id') && $e->getAttribute('id') != '') {
            if (preg_match($this->regexps['negative'], $e->getAttribute('id'))) {
                $weight -= 150;
            }
            if (preg_match($this->regexps['positive'], $e->getAttribute('id'))) {
                $weight += 150;
            }
        }

        $classes = explode(" ", $e->getAttribute('class'));
        /* Look for a special classname */
        foreach ($classes as $class) {
            if (preg_match($this->regexps['negative'], $class)) {
                $weight -= 25;
            }
            if (preg_match($this->regexps['positive'], $class)) {
                $weight += 25;
            }
        }

        return $weight;
    }

    protected function grabArticle($page = null) {
        $articleNode = parent::grabArticle($page);

        if ($articleNode) {
            $articleNode->innerHTML = $this->modifyContent($articleNode->innerHTML);
            $articleNode->innerHTML = strip_tags($articleNode->innerHTML, $this->regexps['allowedTag']);

            //$this->newCounter();
        }
        return $articleNode;
    }

    public function newCounter($node = null) {
        $stripUnlikelyCandidates = $this->flagIsActive(self::FLAG_STRIP_UNLIKELYS);
        if (!$node)
            $node = $this->dom;

        $weight = 0;

        for ($index = 0; $index <= $node . length(); $index++) {
            $node->item($index);
            $weight += getClassWeight($node);
            $weight += newCounter($node->item($index));
        }



        $allElements = $page->getElementsByTagName('*');
    }

    /**
     * Run any post-process modifications to article content as necessary.
     *
     * @param DOMElement
     * @return void
     */
    public function postProcessContent($articleContent) {
        parent::postProcessContent($articleContent);
        foreach ($articleContent->getElementsByTagName('*') as $element) {
            if ($element->hasAttribute('type')) {
                $element->removeAttribute('type');
            }
            if ($element->hasAttribute('href') && preg_match('/^javascript/', $element->getAttribute('href'))) {
                $element->removeAttribute('href');
            }
            if ($element->hasAttribute('rel')) {
                $element->removeAttribute('rel');
            }
            if ($element->hasAttribute('type')) {
                $element->removeAttribute('type');
            }
            if ($element->hasAttribute('content')) {
                $element->removeAttribute('content');
            }
            if ($element->hasAttribute('name')) {
                $element->removeAttribute('name');
            }
            if ($element->hasAttribute('style')) {
                $element->removeAttribute('style');
            }
            if ($element->hasAttribute('target')) {
                $element->removeAttribute('target');
            }
            if ($element->hasAttribute('charset')) {
                $element->removeAttribute('charset');
            }
            if ($element->hasAttribute('onclick')) {
                $element->removeAttribute('onclick');
            }
            if ($element->hasAttribute('class')) {
                $element->removeAttribute('class');
            }
            if ($element->hasAttribute('id')) {
                $element->removeAttribute('class');
            }
        }

        $articleContent->innerHTML = preg_replace('/<a>(.*)<\/a>/i', '$1', $articleContent->innerHTML);
        $articleContent->innerHTML = preg_replace('/<p>[ \n\r\t]*<\/p>/i', '', $articleContent->innerHTML);
        $articleContent->innerHTML = $this->close_xhtml($articleContent->innerHTML);

        // dirty fix
        foreach ($articleContent->childNodes as $item) {
            if ($item->nodeType == XML_PI_NODE) {
                $articleContent->removeChild($item); // remove hack
            }
        }
        $articleContent->encoding = 'UTF-8'; // insert proper
    }

    private function close_xhtml($xhtml) {
        $tags = array();

        for ($i = 0; preg_match('`<(/?)([a-z]+)(?:\s+[a-z]+="[^"]*")*>`i', $xhtml, $tag, PREG_OFFSET_CAPTURE, $i); $i = strlen($tag[0][0]) + $tag[0][1]) {
            if ($tag[1][0] != '/') {
                $tags[] = $tag[2][0];
            } elseif ($tag[2][0] == end($tags)) {
                array_pop($tags);
            } else {
                $xhtml = substr_replace($xhtml, '', $tag[0][1], strlen($tag[0][0]));
            }
        }

        $xhtml = preg_replace('`<[^>]*$`', '', $xhtml);

        while ($tag = array_pop($tags)) {
            $xhtml .= '</' . $tag . '>';
        }

        return $xhtml;
    }

    public function modifyContent($articleContent) {
        global $PICTURES_DOWNLOAD, $PICTURES_BASE64;
        $content = $this->absolutes_links($articleContent);
        if ($PICTURES_DOWNLOAD == true || $PICTURES_BASE64 == true) {
            $content = $this->picture_filtre($content);
        }
        return $content;
    }

    /**
     * On modifie les URLS des images dans le corps de l'article
     */
    private function picture_filtre($content) {
        global $PICTURES_DOWNLOAD, $PICTURES_BASE64;

        $matches = array();
        preg_match_all('#<\s*(img)[^>]+src="([^"]*)"[^>]*>#Si', $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $i => $link) {
            $link[1] = trim($link[1]);
            if (!preg_match('#^(([a-z]+://)|(\#))#', $link[1])) {
                $absolute_path = $this->rel2abs($link[2]);
                $filename = basename(parse_url($absolute_path, PHP_URL_PATH));
                $directory = Utils::create_assets_directory($this->url) . '/' . IMAGES_PATH;
                if (!is_dir($directory)) {
                    mkdir($directory, 0705);
                }

                $fullpath = $directory . '/' . $filename;
                if ($PICTURES_DOWNLOAD) {
                    Utils::download_resources($absolute_path, $fullpath);
                    $content = str_replace($matches[$i][2], $fullpath, $content);
                } else if ($PICTURES_BASE64) {
                    $srcb64 = Utils::pictures_base64($absolute_path, $fullpath);
                    $content = str_replace($matches[$i][2], $srcb64, $content);
                }
            }
        }

        return $content;
    }

    public function absolutes_links($data) {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->encoding = 'UTF-8';

        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">' . $data);
        libxml_use_internal_errors(false);

        // cherche les balises qui contiennent un href ou un src
        $docAbsolute = $this->absolute_for_DOM_and_query($doc, '//*/@src | //*/@href');

        return $docAbsolute->saveHTML();
    }

    public function absolute_for_DOM_and_query($doc, $query) {
        $xpath = new DOMXPath($doc);
        $entries = $xpath->query($query);

        if ($entries != null && count($entries) > 0) {
            foreach ($entries as $entry) {
                $entry->nodeValue = $this->absolute_for_entry($entry->nodeValue);
            }
        }

        return $doc;
    }

    public function absolute_for_entry($nodevalue) {
        $nodevalue = htmlentities(html_entity_decode($nodevalue));
        if (!preg_match('%^((http[s]?://)|(www\.)|(#))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%', $nodevalue)) {
            $nodevalue = $this->rel2abs($nodevalue);
        }
        return $nodevalue;
    }

    public function rel2abs($rel) {
        /* return if already absolute URL */
        if (parse_url($rel, PHP_URL_SCHEME) != '') {
            return $rel;
        }

        /* queries and anchors */
        if ($rel[0] == '#' || $rel[0] == '?') {
            return $this->url . $rel;
        }

        /* parse base URL and convert to local variables:
          $scheme, $host, $path */
        $host = "";
        $scheme = "";
        extract(parse_url($this->url));

        /* remove non-directory element from path */
        $path = preg_replace('#/[^/]*$#', '', $path);

        /* destroy path if relative url points to root */
        if ($rel[0] == '/') {
            $path = '';
        }

        /* dirty absolute URL */
        $abs = "$host$path/$rel";

        /* replace '//' or '/./' or '/foo/../' with '/' */
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for ($n = 1; $n > 0; $abs = preg_replace($re, '/', $abs, -1, $n)) {
            
        }

        /* absolute URL is ready! */
        return $scheme . '://' . $abs;
    }

    public static function retrieveContent($url) {
        // convert page to utf-8
        return Encoding::toUTF8(Utils::get_external_file($url, 15));
    }

}
