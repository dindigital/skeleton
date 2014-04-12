<?php

namespace Admin\Helpers;

use Din\File\Folder;

class MoveFiles
{

  private $_files = array();

  public function addFile ( $origin, $destiny )
  {
    $this->_files[] = array(
        'origin' => 'tmp/' . $origin,
        'destiny' => 'public' . $destiny
    );

  }

  /**
   * Move os arquivos do diretório temporário para o diretório correto.
   * Importante que na chamada ela precisa final depois de todos os addFile
   * e fora de qualquer if.
   */
  public function move ()
  {
    foreach ( $this->_files as $file ) {
      Folder::delete(dirname($file['destiny']));
      Folder::make_writable(dirname($file['destiny']));
      rename($file['origin'], $file['destiny']);
    }

  }

  public function getFiles ()
  {
    return $this->_files;

  }

}
