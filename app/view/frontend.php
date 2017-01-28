<?php

namespace View;

class Frontend {
	protected $template = 'layout.html';

	public function setTemplate( $file ) {
		$this->template = $file;
	}

	public function render() {
		$f3 = \BASE::instance();
		$ret = \Template::instance()->render( $this->template );
		$f3->clear( 'SESSION.messages' );
		$f3->clear( 'SESSION.treeUpdateTrigger' );
		return $ret;
	}
}
