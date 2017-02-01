<?php

/**
 * Description of Saxophone
 *
 *                                                                                                                                           
                                                                     
           0OOOOON                                                 
    NNX00kdk0KXKdl0N                                           
  cccllxKXX    0oxkxX                                         
                OdkOO                                    
                 OdxdO             
                  KxookN                
                   kxloxN                          
                   NdoldxX    KK0KK0O0d
                    kooclo0  0ldc;;coodkK
                     oxloo:X OkX0dc;cok0K                                      
                     XoxcxocNk0X0OxoxN                                         
                      XddcxooxO0OkxoX                                          
                       Xxdloc,:kxollxK                                         
                        Oxoodc:lxc:clN                                         
                        Koxldd:;ooxxdlkN                                       
                         0xocddxkk0Okok                                        
                          XddOOxxO0OxdO                                        
                           0kodkOkxxkX                                         
                             dlkO0N    
 * 
 *                                         
 * @author Juraj Puchk� - Devtech <sjurajpuchky@seznam.cz>
 * @license GPLv3
 * @version 1.0.9
 * @homepage http://www.devtech.cz
 * @copy (c) 2013 Juraj Puchk� - Devtech
 * @description Simplest way to use parser which ever borned, you have to specify object params only then saxophone works for you.
 * Saxophone works with stream support and used about 1MB memory, simply fast as possible. With support of XHTML, XPATH.
 * 
 * @fix 1.0.1 Added support of XHTML, XPATH
 * @fix 1.0.2 Added support of listing supported tags
 * @fix 1.0.3 Added support of remote file size;
 * @fix 1.0.4 Fixed start tag for listing tags support
 * @fix 1.0.5 Fixed access of varname
 * @fix 1.0.6 Fixed access of sax parser
 * @fix 1.0.7 Fixed encoding support
 * @fix 1.0.8 Multiple tag support
 * @fix 1.0.9 Fixed filesize
 * @fix 1.1.0 Added method parse support 
 */
class Saxophone {
	private $url;
	public $prevTag;
	public $currentTag;
	public $currentData;
	public $currentXpath;
	public $supportedTags = array ();
	public $currentAttrs = array ();
	public $currentObject = array ();
	public $currentObjectAttrs = array ();
	public $varname;
	public $objecthandler;
	public $handler;
	private $size;
	private $file;
	public $parser;
	public $xpath = "//";
	public $options = array (
			"object" => "root",
			"encoding" => "utf-8",
			"fields" => array () 
	);
	function stream_open($path, $mode, $options, &$opened_path) {
		$p = explode ( ';', $path );
		$this->url = $p [3];
		$this->size = $this->filesize ( $this->url );
		$url = parse_url ( $p [0] );
		$this->varname = $url ["host"];
		$this->objecthandler = $p [1];
		$this->handler = $p [2];
		if (isset ( $p [4] )) {
			$this->options = $GLOBALS [$p [4]];
		}
		$this->position = 0;
		$GLOBALS [$this->varname] = $this;
		$this->file = fopen ( $this->url, $mode, $options );
		$this->parser = xml_parser_create ( $this->options ["encoding"] );
		xml_set_object ( $this->parser, $this );
		xml_parser_set_option ( $this->parser, XML_OPTION_CASE_FOLDING, false );
		xml_parser_set_option ( $this->parser, XML_OPTION_TARGET_ENCODING, $this->options ["encoding"] );
		xml_set_element_handler ( $this->parser, "Saxophone::startElement", "Saxophone::endElement" );
		xml_set_character_data_handler ( $this->parser, "Saxophone::characterData" );
		return true;
	}
	public static function filesize($url) {
		if (substr ( $url, 0, 4 ) === 'http' || substr ( $url, 0, 5 ) === 'https') {
			$header = array_change_key_case ( get_headers ( $url, 1 ), CASE_LOWER );
			return $header ['content-lenght'];
		} else {
			return filesize ( $url );
		}
	}
	function stream_read($count) {
		$data = fread ( $this->file, $count );
		$this->position += strlen ( $data );
		xml_parse ( $this->parser, $data, feof ( $this->file ) );
		return $data;
	}
	/**
	 * parse data
	 * 
	 * @param String $data        	
	 */
	public function parse($data, $options) {
		$this->options = $options;
		$this->parser = xml_parser_create ( $this->options ["encoding"] );
		xml_set_object ( $this->parser, $this );
		xml_parser_set_option ( $this->parser, XML_OPTION_CASE_FOLDING, false );
		xml_parser_set_option ( $this->parser, XML_OPTION_TARGET_ENCODING, $this->options ["encoding"] );
		xml_set_element_handler ( $this->parser, "Saxophone::startElement", "Saxophone::endElement" );
		xml_set_character_data_handler ( $this->parser, "Saxophone::characterData" );
		
		xml_parse ( $this->parser, $data, true );
	}
	function stream_write($data) {
		return 0;
	}
	function stream_tell() {
		return $this->position;
	}
	function stream_eof() {
		return feof ( $this->file );
	}
	function stream_close() {
		fclose ( $this->file );
		xml_parser_free ( $this->parser );
	}
	function stream_seek($offset, $whence) {
		switch ($whence) {
			case SEEK_SET :
				if ($offset < $this->size && $offset >= 0) {
					$this->position = $offset;
					return true;
				} else {
					return false;
				}
				break;
			
			case SEEK_CUR :
				if ($offset >= 0) {
					$this->position += $offset;
					return true;
				} else {
					return false;
				}
				break;
			
			case SEEK_END :
				if ($this->size + $offset >= 0) {
					$this->position = $this->size + $offset;
					return true;
				} else {
					return false;
				}
				break;
			
			default :
				return false;
		}
	}
	function stream_metadata($path, $option, $var) {
		return true;
	}
	function startElement($parser, $name, $attrs) {
		$this->currentData = "";
		switch (strtoupper ( $this->options ["type"] )) {
			case "TAGSLIST" :
				break;
			default :
				if ($this->options ["object"] == $name) {
					$this->currentObject = array ();
				}
				
				$condition = "";
				foreach ( $attrs as $attr => $value ) {
					if ($condition == "") {
						switch (strtoupper ( $attr )) {
							case "ID" :
								$condition .= "$attr='$value',";
								break;
							case "NAME" :
								$condition .= "$attr='$value',";
								break;
							case "CLASS" :
								$condition .= "$attr='$value',";
								break;
							default :
						}
					}
				}
				
				$this->xpath .= "/$name" . ($condition != "" ? "[" . $condition . "]" : "");
				$this->prevTag = $this->currentTag;
				$this->currentTag = $name;
				$this->currentAttrs = $attrs;
		}
	}
	function endElement($parser, $name) {
		switch (strtoupper ( $this->options ["type"] )) {
			case "TAG" :
				foreach ( $this->options ["fields"] as $key => $tag ) {
					if ($name === $tag) {
						// Duplicity of field, because of feed format
						if ($this->currentData !== "") {
							$this->currentObject [$key] = $this->currentData;
						}
						
						$this->currentObjectAttrs [$key] = $this->currentAttrs;
					}
				}
				if ($this->options ["object"] == $name) {
					call_user_func ( $this->objecthandler, $this->currentObject, $this->currentObjectAttrs, $this );
				} else {
					call_user_func ( $this->handler, $name, $this->currentData, $this->currentAttrs, $this );
				}
				break;
			case "XPATH" :
				foreach ( $this->options ["fields"] as $key => $xpath ) {
					if ($this->xpath_match ( $this->xpath, $xpath )) {
						if ($this->currentData !== "") {
							$this->currentObject [$key] = $this->currentData;
						}
						$this->currentObjectAttrs [$key] = $this->currentAttrs;
					}
				}
				if ($this->xpath_match ( $this->xpath, $this->options ["object"] )) {
					call_user_func ( $this->objecthandler, $this->currentObject, $this->currentObjectAttrs, $parser );
				} else {
					call_user_func ( $this->handler, $name, $this->currentData, $this->currentAttrs, $parser );
				}
				break;
			case "TAGSLIST" :
				$this->supportedTags [$name] = $name;
				break;
		}
		$this->currentTag = $name;
		$this->xpath = preg_replace ( "/\/$name.*$/", "", $this->xpath, 1 );
	}
	private function xpath_match($xpath, $subject) {
		$fsubject1 = str_replace ( "*", ".*", $subject );
		$fsubject2 = str_replace ( "/", "\/", $fsubject1 );
		$fsubject3 = str_replace ( "[", "\[", $fsubject2 );
		$fsubject = str_replace ( "]", "\]", $fsubject3 );
		$fsubject .= "$";
		return preg_match ( '/^' . $fsubject . '/', $xpath );
	}
	function characterData($parser, $data) {
		$this->currentData .= $data;
	}
}

stream_wrapper_register ( "sax", "Saxophone" ) or die ( "Failed to register saxophone parser" );

?>
