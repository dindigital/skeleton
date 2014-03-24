<?php

namespace src\app\admin\helpers;

class Entity
{

  protected $_tbl;
  protected $_entity;

  public function __construct ( $tbl, $array )
  {
    $this->_tbl = $tbl;
    $this->setEntity($array);
  }

  protected function setEntity ( $array )
  {
    $this->_entity = $array;
  }

  protected function returnField ( $field )
  {
    if ( array_key_exists($field, $this->_entity) ) {
      return $this->_entity[$field];
    }
  }

  public function getModel ()
  {
    $namespace = '\src\app\admin\models\\';

    return $namespace . $this->returnField('model');
  }

  public function getSection ()
  {
    return $this->returnField('section');
  }

  public function getTitle ()
  {
    return $this->returnField('title');
  }

  public function hasTrash ()
  {
    return $this->returnField('trash') == true;
  }

  public function getId ()
  {
    return $this->returnField('id');
  }

  public function getChildren ()
  {
    return (array) $this->returnField('children');
  }

  public function getTbl ()
  {
    return $this->_tbl;
  }

}
