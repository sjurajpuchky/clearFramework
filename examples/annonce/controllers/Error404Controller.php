<?php
class Error404Controller extends Controller {
	public function process($params) {
		header ( "HTTP/1.0 404 Not Found" );
		$this->header ['title'] = 'Chyba 404';
		$this->view = 'error404';
	}
}