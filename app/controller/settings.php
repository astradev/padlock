<?php

namespace Controller;

class Settings extends Base {
	public function all( \Base $f3, $params ) {
	    // template information
            $f3->set( 'content', 'settings.html' );
            $f3->set( 'pagetitle', 'PADLOCK - Settings' );            
	}
}
