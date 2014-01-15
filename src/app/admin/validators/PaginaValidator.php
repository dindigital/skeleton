<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\PaginaTable;
use Din\Exception\JsonException;

class PaginaValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new PaginaTable();
  }

  public function setIdPagina ()
  {
    $this->_table->id_pagina = $this->_table->getNewId();

    return $this;
  }

  public function setIdPaginaCat ( $id_pagina_cat )
  {
    $this->_table->id_pagina_cat = $id_pagina_cat;

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
