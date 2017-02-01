<?php
/**
 * 
 * @author Juraj Puchký
 */
class Seo {
	/**
	 * Convert string to uri
	 * 
	 * @param unknown $param        	
	 * @return string
	 */
function paramUri($param) {
	// Convert to ascii
	setlocale ( LC_CTYPE, 'cs_CZ' );
	$iparam = iconv ( 'UTF-8', 'ASCII//TRANSLIT', $param );
	// Replace spaces
	$sparam = preg_replace ( '/,+/', '-', $iparam );
	$sparam = preg_replace ( '/\/+/', '-', $sparam );
	$sparam = preg_replace ( '/ +/', '-', $sparam );
	$sparam = preg_replace ( '/_+/', '-', $sparam );
	$sparam = preg_replace ( '/-+/', '-', $sparam );
	// Remove accent
	$sparam = str_replace ( "'", "", $sparam );
	// Lowercase
	$lparam = strtolower ( $sparam );
	return preg_replace ( '/-*$/', '', $lparam );
}
	/**
	 * Select URI helper
	 * @param unknown $uri
	 * @return Ambigous <>
	 */
	public function selectUri($uri) {
		$euri = explode('?',$uri);
		$turi = trim ( $euri[0], "/" );
		$suri = explode ( "/", $turi );
		return strtolower($suri [0]);
	}
	/**
	 * Optimize text with backlinks
	 * @param unknown $text
	 * @return mixed
	 */
	public function optimizeText($text) {
		global $pzdb;
		// Remove undefined strings
		$text = preg_replace ( '/undefined/i', '', $text );
		$keywords = $pzdb->getResults ( "SELECT keyword,class FROM " . DB_TABLE_PREFIX . "keywords ORDER by score;" );
		foreach ( $keywords as $record ) {
			$text = preg_replace ( '/' . $record ["keyword"] . '/i', '<a href="' . APP_WEB_URL . 'hledej?q=' . $record ["keyword"] . '" class="'.$record["class"].'">' . $record ["keyword"] . '</a>', $text );
		}
		return $text;
	}
	/**
	 * Optimize category text
	 * @param unknown $uri
	 * @return Ambigous <>|mixed
	 */
	public function optimizeCategoryDesc($uri) {
		global $pzdb;
		$categories= $pzdb->getResults("SELECT * FROM ".DB_TABLE_PREFIX."categories WHERE uri='".$uri."';");
		foreach ($categories as $category) {
			if($category["ostamp"] > $category["stamp"]) {
				return $category["ocontent"];
			} else {
				$description = Seo::optimizeText($category["content"]);
				$assoc = array();
				$assoc["ostamp"] = time();
				$assoc["odescription"] = addslashes($description);
				$pzdb->update(DB_TABLE_PREFIX."categories", $assoc, "uri='".$uri."'");
				return $description;
			}
		}
		
	}
	/**
	 * Optimize post text
	 * @param unknown $uri
	 * @return Ambigous <>|mixed
	 */
	public function optimizePost($uri) {
		global $pzdb;
		$posts= $pzdb->getResults("SELECT * FROM ".DB_TABLE_PREFIX."posts WHERE uri='".$uri."';");
		foreach ($posts as $post) {
			if($post["ostamp"] > $post["stamp"]) {
				return $post["ocontent"];
			} else {
				$text = Seo::optimizeText($post["content"]);
				$assoc = array();
				$assoc["ostamp"] = time();
				$assoc["ocontent"] = addsleshes($text);
				$pzdb->update(DB_TABLE_PREFIX."posts", $assoc, "uri='".$uri."'");
				return $text;
			}
		}
	
	}
	public function lastParamUri($uri) {
		$euri = explode('?',$uri);
		$turi = trim ( $euri[0], "/" );
		$suri = explode ( "/", $turi );
		return strtolower($suri [count($suri) - 1]);
	}
	/**
	 * Collection of page keywords
	 * @param unknown $uri
	 */
	public function keywordsCollection($uri) {
		
	}
}