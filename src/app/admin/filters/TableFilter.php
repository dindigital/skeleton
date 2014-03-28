<?php

namespace src\app\admin\filters;

use Din\Crypt\Crypt;
use Din\Filters\String\Uri;
use Din\Filters\String\LimitChars;
use Din\Filters\Date\DateToSql;
use Din\Exception\JsonException;

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

  public function setUploaded ( $field, $path )
  {
    $value = $this->getValue($field);
    $file = $value[0];

    $pathinfo = pathinfo($file['name']);
    $name = \Din\Filters\String\Uri::format($pathinfo['filename']) . '.' . $pathinfo['extension'];

    $this->_table->{$field} = "{$path}/{$name}";
  }

  public function setDefaultUri ( $title_field, $id, $prefix = '' )
  {
    $title = $this->getValue($title_field);
    $uri = $this->getValue('uri');
    $id = substr($id, 0, 4);

    $uri = $uri == '' ? Uri::format($title) : Uri::format($uri);
    $uri = LimitChars::filter($uri, 80, '');
    if ( $prefix != '' ) {
      $prefix = '/' . $prefix;
    }

    $this->_table->uri = "{$prefix}/{$uri}-{$id}/";
  }

  public function setNavUri ( $title_field )
  {
    $title = $this->getValue($title_field);
    $uri = $this->getValue('uri');

    $uri = $uri == '' ? Uri::format($title) : Uri::format($uri);
    $uri = LimitChars::filter($uri, 80, '');
    
    if ($uri != '') {
        $uri .= '/';
    }

    $this->_table->uri = "/$uri";
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
