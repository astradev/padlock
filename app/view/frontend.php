<?php

namespace View;

class Frontend {
	protected $template = 'layout.html';

	public function setTemplate( $file ) {
		$this->template = $file;
	}

	public function render() {
		return \Template::instance()->render( $this->template );
	}
}
