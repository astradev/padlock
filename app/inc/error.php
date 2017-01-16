<?php

class Error {
	public function handle() {
		$f3 = \Base::instance();
		echo $f3->get('ERROR.text') . "<br>";
		echo $f3->get('ERROR.code') . "<br>";
		echo $f3->get('ERROR.status') . "<br>";
		echo $f3->get('ERROR.text') . "<br>";
		echo "<pre>";
		print_r( $f3->get('ERROR.trace') );
		echo "</pre>";
	}
}
