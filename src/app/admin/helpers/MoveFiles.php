<?php

namespace src\app\admin\helpers;

use Din\File\Folder;

class MoveFiles
{

  private $_files = array();

  public function addFile ( $origin, $destiny )
  {
    $this->_files[] = array(
        'origin' => $origin,
        'destiny' => $destiny,
    );
  }

  public function move ()
  {
    foreach ( $this->_files as $file ) {
      Folder::make_writable(dirname($file['destiny']));
      rename($file['origin'], $file['destiny']);
    }
  }

}
