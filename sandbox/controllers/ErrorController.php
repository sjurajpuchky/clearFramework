<?php
class ErrorController extends Controller {
	public function process($params) {
		header ( "HTTP/1.0 500 Application Error" );
		$this->header ['title'] = 'Chyba 500';
		$this->view = 'error';
	}
}