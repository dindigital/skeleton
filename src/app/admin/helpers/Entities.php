<?php

namespace src\app\admin\helpers;

use src\app\admin\helpers\Entity;
use Din\File\Files;
use Exception;

class Entities
{

  protected $_entities;

  public function __construct ( $file )
  {
    $this->setEntities($file);
  }

  protected function setEntities ( $file )
  {
    if ( !Files::exists($file) )
      throw new Exception('Arquivo de entidades não encontrado: ' . $file);

    $this->_entities = Files::get_return($file);
  }

  public function getEntity ( $tbl )
  {
    if ( array_key_exists($tbl, $this->_entities) ) {
      return new Entity($tbl, $this->_entities[$tbl]);
    } else {
      throw new Exception('Entidade desconhecida: ' . $tbl);
    }
  }

  public function getAllEntities ()
  {
    $r = array();
    foreach ( $this->_entities as $tbl => $item ) {
      $r[$tbl] = new Entity($tbl, $item);
    }

    return $r;
  }

  public function getTrashItens ()
  {
    $r = array();
    foreach ( $this->_entities as $tbl => $item ) {
      if ( isset($item['trash']) && $item['trash'] ) {
        $r[$tbl] = new Entity($tbl, $item);
      }
    }

    return $r;
  }

  public function getSectionItens ()
  {
    $r = array();
    foreach ( $this->_entities as $tbl => $item ) {
      if ( isset($item['section']) ) {
        $r[$tbl] = new Entity($tbl, $item);
      }
    }

    return $r;
  }

}
