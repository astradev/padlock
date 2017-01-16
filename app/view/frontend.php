<?php

namespace View;

class Frontend {
	public function render() {
          //echo file_get_contents('../app/ui/templates/layout.html');
	  //return \Template::instance()->render('../app/ui/templates/layout.html');
          return \Template::instance()->render('layout.html');
          //return $e;
	}
}
