<?php

namespace Controller;

class Config extends Backend {

	public function __construct( $filename, $safefile = false ) {
		parent::__construct();
		$f3 = \BASE::instance();
    
		$f3->set( 'component', 'config' );
	}

	public function config_set( $config, $section, $key, $value ) {
		$configData = parse_ini_file( $config, true );
		$configData[$section][$key] = $value;

		$content = '';

		foreach( $configData as $section => $sectionContent ) {
		  $sectionContent = array_map( function( $value, $key ) {
		    return "$key=$value";
		  }, array_values( $sectionContent ), array_key( $sectionContent ) );
		  $sectionContent = implode( "\n", $sectionContent );
		  $content .= "[$section]\n$sectionContent\n";
		}
		file_put_contents( $config, $content );
	}		

	public function show( \Base $f3 ) {
		$f3->set( 'section', 'config.html' );
	}

}

