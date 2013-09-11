<?php

class Readityourself extends Readability {
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
		} catch(Exception $e) {}
		
		if (preg_match('/ [\|\-] /', $curTitle))
		{
			$curTitle = preg_replace('/(.*)[\|\-] .*/i', '$1', $origTitle);
			
			if (count(explode(' ', $curTitle)) < 3) {
				$curTitle = preg_replace('/[^\|\-]*[\|\-](.*)/i', '$1', $origTitle);
			}
		}

        // remove to correct split of title with ':' see https://github.com/memiks/readityourself/issues/1
        /*
		else if (strpos($curTitle, ': ') !== false)
		{
			$curTitle = preg_replace('/.*:(.*)/i', '$1', $origTitle);

			if (count(explode(' ', $curTitle)) < 3) {
				$curTitle = preg_replace('/[^:]*[:](.*)/i','$1', $origTitle);
			}
		}
        */

		else if(strlen($curTitle) > 150 || strlen($curTitle) < 15)
		{
			$hOnes = $this->dom->getElementsByTagName('h1');
			if($hOnes->length == 1)
			{
				$curTitle = $this->getInnerText($hOnes->item(0));
			}
		}

		$curTitle = trim($curTitle);

		if (count(explode(' ', $curTitle)) <= 4) {
			$curTitle = $origTitle;
		}
		
		$articleTitle = $this->dom->createElement('h1');
		$articleTitle->innerHTML = $curTitle;
		
		return $articleTitle;
	}
	
}
