<?php

namespace Controller;

class Folders extends Base {

  protected $response;

  public function newfolder( $f3,$params ) {
    if( $f3->exists( 'POST.name' ) ) {
      $folder = new \Model\Folder();
      $folder->reset();
      $folder->name = $f3->get( 'POST.name' );
      if( $f3->exists( 'POST.parent_id' ) && is_int( $f3->get( 'POST.parent_id' ) ) ) {
        $folder->parent_id = $f3->get( 'POST.parent_id' );
      }
      $folder->save();
      $f3->messages[] = array( "Folder was successfully created.", 0 );
      $f3->set( 'content', 'dashboard.html');
    } else {
      $f3->set( 'content', 'newfolder.html' );
    }
  }

  public function manage( $f3, $params ) {

  }

  public function delete( $f3, $params ) {
    if( $f3->exists( 'POST.id' ) && is_int( $f3->get( 'POST.id' ) ) ) {
      $folder = new \Model\Folder( $params['id'] );
      $folder->delete();
      $f3->messages[] = array( "Folder was successfully deleted", 0 );
      $f3->reroute( '/dashboard' );
    } else {
      $f3->messages[] = array("Could not delete folder: No valid folder id given", 1 );
      $f3->reroute( '/dashboard' );
    }
  }

} 
