<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;

class PaginaCatValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('pagina_cat');
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
