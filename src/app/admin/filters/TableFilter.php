<?php

namespace src\app\admin\filters;

use Din\Crypt\Crypt;
use Din\Filters\String\Uri;
use Din\Filters\String\LimitChars;
use Din\Filters\Date\DateToSql;
use Din\File\Folder;
use src\app\admin\helpers\MoveFiles;

class TableFilter extends BaseFilter
{

  public function setNewId ( $field )
  {
    return $this->_table->{$field} = md5(uniqid());
  }

  public function setTimestamp ( $field )
  {
    $this->_table->{$field} = date('Y-m-d H:i:s');
  }

  public function setIntval ( $field )
  {
    $value = intval($this->getValue($field));

    $this->_table->{$field} = $value;
  }

  public function setString ( $field )
  {
    $value = (string) $this->getValue($field);

    $this->_table->{$field} = $value;
  }

  public function setDate ( $field )
  {
    $value = $this->getValue($field);

    $this->_table->{$field} = DateToSql::filter_date($value);
  }

  public function setJson ( $field )
  {
    $value = (array) $this->getValue($field);

    $this->_table->{$field} = json_encode($value);
  }

  public function setCrypted ( $field )
  {
    $value = $this->getValue($field);

    if ( $value != '' ) {
      $crypt = new Crypt();
      $this->_table->{$field} = $crypt->crypt($value);
    }
  }

  public function setNull ( $field )
  {
    $this->_table->{$field} = null;
  }

  public function setUploaded ( $field, $path, $has_upload, MoveFiles $mf )
  {
    if ($has_upload) {
        $value = $this->getValue($field);
        $file = $value[0];
        $pathinfo = pathinfo($file['name']);
        $name = Uri::format($pathinfo['filename']) . '.' . $pathinfo['extension'];
        $this->_table->{$field} = "{$path}/{$name}";
        $mf->addFile($file['tmp_name'], $this->_table->{$field});
    }
    $this->deleteUploadFolder($field, $path);
  }
  
  protected function deleteUploadFolder ($field, $path) 
  {
    if (array_key_exists("{$field}_delete", $this->_input) ) {
      if ($this->getValue("{$field}_delete")) {
        Folder::delete($path);
        $this->_table->{$field} = null;
      }
    }
  }

  public function setDefaultUri ( $title_field, $id, $prefix = '' )
  {
    $title = $this->getValue($title_field);
    $uri = $this->getValue('uri');
    $id = substr($id, 0, 4);

    $uri = $uri == '' ? Uri::format($title) : Uri::format($uri);
    $uri = LimitChars::filter($uri, 80, '');
    if ( $prefix != '' ) {
      $prefix = '/' . Uri::format($prefix);
    }

    $this->_table->uri = "{$prefix}/{$uri}-{$id}/";
  }

  public function setNavUri ( $title_field, $prefix = '' )
  {      
    $title = $this->getValue($title_field);
    $uri = $this->getValue('uri');

    $uri = $uri == '' ? Uri::format($title) : Uri::format($uri);
    $uri = LimitChars::filter($uri, 80, '');
    
    if ($uri != '') {
        $uri .= '/';
    }
    
    if ( $prefix != '' ) {
      $prefix = '/' . Uri::format($prefix);
    }
    
    $this->_table->uri = "$prefix/$uri";
  }

  public function setIdParent ()
  {
    $id_parent = $this->getValue('id_parent');

    if ( count($id_parent) ) {
      $last = end($id_parent);
      if ( $last == '0' ) {
        if ( isset($id_parent[count($id_parent) - 2]) ) {
          $this->_table->id_parent = $id_parent[count($id_parent) - 2];
        }
      } else {
        $this->_table->id_parent = end($id_parent);
      }
    }
  }

  /*
    public function setShortenerLink ()
    {
    if ( URL && BITLY && $this->_table->uri ) {
    $url = URL . $this->_table->uri;
    $bitly = new Bitly(BITLY);
    $bitly->shorten($url);
    if ( $bitly->check() ) {
    $this->_table->short_link = $bitly;
    }
    }
    }
   */
}
