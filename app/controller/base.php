<?php

namespace Controller;

abstract class Base {

	protected $response;

	public function beforeroute($f3) {
		$this->response = new \View\Frontend();
	}

	public function afterroute() {
		if (!$this->response)
			trigger_error('No View has been set.');
		echo $this->response->render();
	}
}
