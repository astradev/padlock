<?php

namespace Controller;

class Test {
	protected $response;

	public function beforeroute( $f3 ) {
		$this->response = new \View\Frontend();
	}

	public function main( \Base $f3, $params ) {
		$f3->set( 'TESTVAR', "Variableninhalt"  );
	}

	public function afterroute( $f3 ) {
		echo $this->response->render();
	}
}
