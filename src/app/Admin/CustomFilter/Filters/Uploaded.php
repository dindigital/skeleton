<?php

namespace Admin\CustomFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Admin\Helpers\MoveFiles;
use Din\Filters\String\Uri;
use Din\File\Folder;

class Uploaded extends AbstractFilter
{

  protected $_path;
  protected $_has_upload;
  protected $_mf;

  public function __construct ( $path, $has_upload, MoveFiles $mf )
  {
    $this->_path = $path;
    $this->_has_upload = $has_upload;
    $this->_mf = $mf;

  }

  public function filter ( $field )
  {
    if ( $this->_has_upload ) {
      $value = $this->getValue($field);
      $file = $value[0];
      $pathinfo = pathinfo($file['name']);
      $name = Uri::format($pathinfo['filename']) . '.' . $pathinfo['extension'];
      $this->_table->{$field} = "{$this->_path}/{$name}";
      $this->_mf->addFile($file['tmp_name'], $this->_table->{$field});
    }

    $this->deleteUploadFolder($field);

  }

  protected function deleteUploadFolder ( $field )
  {
    if ( array_key_exists("{$field}_delete", $this->_input) ) {
      if ( $this->getValue("{$field}_delete") ) {
        Folder::delete($this->_path);
        $this->_table->{$field} = null;
      }
    }

  }

}
