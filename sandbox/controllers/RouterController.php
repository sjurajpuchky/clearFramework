<?php
class RouterController extends Controller {
	public $controller;
	protected $view = "main";
	public function process($params) {
		$parsedURL = $this->parseUrl ( $params );
		
		if (empty ( $parsedURL [0] )) {
			$this->view = "mainpage";
		} else {
			$controllerClass = $this->convertUri2Class ( array_shift ( $parsedURL ) ) . 'Controller';
			try {
				if (file_exists ( 'controllers/' . $controllerClass . '.php' ))
					$this->controller = new $controllerClass ();
				else
					$this->redirect ( 'error404' );
			} catch ( Exception $ex ) {
				$this->redirect ( 'error' );
			}
			$this->controller->process ( $parsedURL );
			$this->data ['title'] = $this->controller->header ['title'];
			$this->data ['description'] = $this->controller->header ['description'];
			$this->data ['keywords'] = $this->controller->header ['keywords'];
		}
	}
}