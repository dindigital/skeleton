<?php

namespace src\app\admin\models\essential;

use Din\File\Files;
use Exception;

class FileMenu
{

  protected $_file;
  protected $_menu_array;

  public function __construct ( $file )
  {
    $this->setFile($file);
  }

  public function setFile ( $file )
  {
    if ( !Files::exists($file) )
      throw new Exception('Arquivo de menu não encontrado: ' . $file);

    $this->_file = $file;
  }

  protected function setMenuArray ()
  {
    $this->_menu_array = Files::get_return($this->_file);
  }

  public function getArray ()
  {
    $this->setMenuArray();

    return $this->_menu_array;
  }

}
