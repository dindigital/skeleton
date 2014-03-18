<?php

namespace src\app\admin\helpers;

use Din\File\Folder;

class MoveFiles
{

  private $_files = array();

  public function addFile ( $origin, $destiny, $delete = null )
  {
    $this->_files[] = array(
        'origin' => 'tmp/' . $origin,
        'destiny' => 'public' . $destiny,
        'delete' => 'public' . $delete
    );
  }

  public function move ()
  {
    foreach ( $this->_files as $file ) {
      if ( $file['delete'] ) {
        @unlink($file['delete']);
      }

      Folder::make_writable(dirname($file['destiny']));
      rename($file['origin'], $file['destiny']);
    }
  }

  public function getFiles ()
  {
    return $this->_files;
  }

}
