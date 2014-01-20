<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Respect\Validation\Validator as v;
use Din\Exception\JsonException;

class ConfiguracaoValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('configuracao');
  }

  public function setTitleHome ( $title_home )
  {

    if ( !v::string()->notEmpty()->validate($title_home) )
      JsonException::addException('Title Home é obrigatório');

    $this->_table->title_home = $title_home;
  }

  public function setDescriptionHome ( $description_home )
  {
    if ( !v::string()->notEmpty()->validate($description_home) )
      JsonException::addException('Description Home é obrigatório');

    $this->_table->description_home = $description_home;
  }

  public function setKeywordsHome ( $keywords_home )
  {
    if ( !v::string()->notEmpty()->validate($keywords_home) )
      JsonException::addException('Keywords Home é obrigatório');

    $this->_table->keywords_home = $keywords_home;
  }

  public function setTitleInterna ( $title_interna )
  {
    if ( !v::string()->notEmpty()->validate($title_interna) )
      JsonException::addException('Title Internas é obrigatório');

    $this->_table->title_interna = $title_interna;
  }

  public function setDescriptionInterna ( $description_interna )
  {
    if ( !v::string()->notEmpty()->validate($description_interna) )
      JsonException::addException('Description Internas é obrigatório');

    $this->_table->description_interna = $description_interna;
  }

  public function setKeywordsInterna ( $keywords_interna )
  {
    if ( !v::string()->notEmpty()->validate($keywords_interna) )
      JsonException::addException('Keywords Internas é obrigatório');

    $this->_table->keywords_interna = $keywords_interna;
  }

  public function setQtdHoras ( $qtd_horas )
  {
    if ( !v::numeric()->validate($qtd_horas) )
      JsonException::addException('Qtd. hora deve ser um número maior ou igual a 0');

    $this->_table->qtd_horas = $qtd_horas;
  }

  public function setEmailAvisos ( $email_avisos )
  {
    if ( !v::email()->validate($email_avisos) )
      JsonException::addException('E-mail avisos deve ser um e-mail válido');

    $this->_table->email_avisos = $email_avisos;
  }

}
