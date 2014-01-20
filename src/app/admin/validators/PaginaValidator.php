<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\PaginaTable;
use Din\Exception\JsonException;
use src\app\admin\models\PaginaCatModel;

class PaginaValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new PaginaTable();
  }

  public function setIdPaginaCat ( $id_pagina_cat )
  {
    $pagina_cat = new PaginaCatModel();
    $count = $pagina_cat->getCount($id_pagina_cat);
    if ( $count == 0 )
      return JsonException::addException('Menu não encontrado.');

    $this->_table->id_pagina_cat = $id_pagina_cat;

    return $this;
  }

  public function setIdParent ( $id_parent )
  {
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

    return $this;
  }

  public function setTitulo ( $titulo )
  {
    if ( $titulo == '' )
      return JsonException::addException('Titulo é obrigatório');

    $this->_table->titulo = $titulo;
  }

  public function setConteudo ( $conteudo )
  {
    $this->_table->conteudo = $conteudo;
  }

  public function setDescription ( $description )
  {
    $this->_table->description = $description;
  }

  public function setKeywords ( $keywords )
  {
    $this->_table->keywords = $keywords;
  }

}
