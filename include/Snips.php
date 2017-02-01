<?php

/**
 * 
 * @author Juraj Puchký
 *
 */
class Snips {
	public function isParametrized($uri) {
		return strpos ( $uri, '?' ) > 0 ? true : false;
	}
	/**
	 * Pagging snip
	 * @param number $currentPageFrom
	 * @param number $pageTotal - total results
	 * @param number $paggingBy
	 * @param number $maxPages
	 */
	public function paggingSnip($currentPageFrom, $pageTotal, $paggingBy, $maxPages = 10) {
		$pagerTotal = $pageTotal / $paggingBy;
		if($pagerTotal > $maxPages) {
			$pagerTotal = $maxPages;
		}
		if (strpos ( $_SERVER ["REQUEST_URI"], '?from=' ) > 0) {
			$uriBase = substr ( $_SERVER ["REQUEST_URI"], 0, strpos ( $_SERVER ["REQUEST_URI"], '?from=' ) );
		} else {
			if (strpos ( $_SERVER ["REQUEST_URI"], '&from=' ) > 0) {
				$uriBase = substr ( $_SERVER ["REQUEST_URI"], 0, strpos ( $_SERVER ["REQUEST_URI"], '&from=' ) );
			} else {
				$uriBase = $_SERVER ["REQUEST_URI"];
			}
		}
		// if (strpos ( $_SERVER ["REQUEST_URI"], '?from=' ) > 0) {
		// $uriBase = substr ( $_SERVER ["REQUEST_URI"], 0, strpos ( $_SERVER ["REQUEST_URI"], '?from=' ) );
		// }
		echo '<div class="paggingBox">';
		echo '<a class="paggingBoxPage" href="' . $uriBase . (Snips::isParametrized ( $uriBase ) ? '&' : '?') . 'from=' . ($currentPageFrom - $paggingBy > 0 ? $currentPageFrom - $paggingBy : 0) . '&by=' . $paggingBy . '"><</a>';
		for($paggingFrom = 0; $paggingFrom < ($pagerTotal * $paggingBy); $paggingFrom += $paggingBy) {
			if ($paggingFrom === $currentPageFrom) {
				echo '<div class="paggingBoxPage">' . $paggingFrom / $paggingBy . '</div>';
			} else {
				echo '<a class="paggingBoxPage" href="' . $uriBase . (Snips::isParametrized ( $uriBase ) ? '&' : '?') . 'from=' . $paggingFrom . '&by=' . $paggingBy . '">' . $paggingFrom / $paggingBy . '</a>';
			}
		}
		echo '<a class="paggingBoxPage" href="' . $uriBase . (Snips::isParametrized ( $uriBase ) ? '&' : '?') . 'from=' . ($currentPageFrom + $paggingBy > $pageTotal ? $currentPageFrom : $currentPageFrom + $paggingBy) . '&by=' . $paggingBy . '">></a>';
		echo '</div>';
	}
	/**
	 * Normalize name
	 * @param string $name
	 * @param string $encoding
	 * @return string
	 */
	public function normalizeName($name, $encoding = "UTF-8") {
		return ucfirst ( mb_strtolower ( $name, $encoding ) );
	}
	/**
	 * Make text short snip
	 * @param unknown $text
	 * @param number $size
	 * @return string
	 */
	public function shortText($text, $size = 360) {
		return substr ( $text, 0, $size ) . "...";
	}
	/**
	 * Display product short detail
	 * @param array $product - product from collection of products
	 */
	public function productShortDetail($product) {
		global $pzdb;
		//$pmetas = $pzdb->getResults ( "select * from " . DB_TABLE_PREFIX . "productMeta where Name='" . $product ["Name"] . "' and feed='" . $product ["feed"] . "';" );
		//$productMeta = $pzdb->metaAsArray ( $pmetas );
		
		?>
<div class="product">
	<h1 class="productTitle"><?php echo Snips::normalizeName($product["Name"]);?></h1>
	<h2 class="productPrice"><?php echo $product["PriceVat"];?></h2>
	<p class="productDesc"><?php echo Seo::optimizeText(Snips::shortText($product["Description"]));?></p>
</div>
<?php
	}
	public function categoryShortDetail($category) {
		?>
<div class="category">
		<a class="catgoryTitle"
			href="<?php echo APP_WEB_URL."kategorie/".$category["uri"];?>">
			<h2 class="categoryTitle"><?php echo $category["category"];?></h2></a>
	<p><?php echo Seo::optimizeCategoryDesc($category["uri"]); ?></p>
</div>
<?php
	}
	public function productLongDetail($product) {
	}
}