<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;

class LogValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('log');
  }

  public function setAdministrador ( $administrador )
  {
    $this->_table->administrador = $administrador;
  }

  public function setName ( $name )
  {
    $this->_table->name = $name;
  }

  public function setAcao ( $acao )
  {
    $this->_table->acao = $acao;
  }

  public function setDescricao ( $descricao )
  {
    $this->_table->descricao = $descricao;
  }

  public function setConteudo ( $conteudo )
  {
    $this->_table->conteudo = $conteudo;
  }

}
