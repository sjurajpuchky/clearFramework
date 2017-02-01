<?php

abstract class Controller
{

    protected $data = array();
    protected $view = "";
	protected $header = array('title' => '', 'keywords' => '', 'description' => '');

    public function processView()
    {
        if ($this->view)
        {
            extract($this->data);
            require(__DIR__."/../views/" . $this->view . ".phtml");
        }
    }
    
    public function parseUrl($url) {
    	$parsedURL = parse_url ( $url );
    	$parsedURL ["path"] = ltrim ( $parsedURL ["path"], "/" );
    	$parsedURL ["path"] = trim ( $parsedURL ["path"] );
    	$explodedPath = explode ( "/", $parsedURL ["path"] );
    	return $explodedPath;
    }
    
    public function convertUri2Class($uri)
    {
    	$class = str_replace('-', ' ', $uri);
    	$class = ucwords($class);
    	$class = str_replace(' ', '', $class);
    	return $class;
    }	
	public function redirect($uri)
	{
		header("Location: /$uri");
		header("Connection: close");
        exit;
	}
	public function redirectURL($url) {
		header("Location: $url");
		header("Connection: close");
		exit;
	}
    abstract function process($params);

}