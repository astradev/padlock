<?php

namespace Controller;

class Config extends Backend {

	public function show( \Base $f3, $params ) {
		$f3->set( 'section', 'config.html' );
	}

}

